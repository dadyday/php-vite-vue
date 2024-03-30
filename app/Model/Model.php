<?php

namespace App\Model;

use Nette;
use Nette\Database\Explorer;
use Nette\Database\Conventions;
use Nette\Caching\Storage;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

abstract class Model extends Selection {

	static string $table;
	static string $class;

	function __construct(
		protected Explorer $oDb,
		Conventions $oConventions,
		?Storage $oCacheStorage = null
	) {
		parent::__construct($oDb, $oConventions, static::$table, $oCacheStorage);

		# $this->dropTable();
		# $this->createTable();
		# if (!count($this)) {
		# 	$this->createData();
		# }
	}

	protected function createRow(array $row): ActiveRow	{
		return new static::$class($row, $this);
	}

	function dropTable(): void  {
		$this->oDb->query("DROP TABLE IF EXISTS ?name", static::$table);
	}

	function createTable(): void {
		$sql = '';
		$aField = (static::$class)::$aField;
		foreach ($aField as $name => [$type]) {
			$sql .= ",\n  $name $type";
		}

		$this->oDb->query("
			CREATE TABLE IF NOT EXISTS ?name (
				id INTEGER PRIMARY KEY $sql
			)
		", static::$table);
	}

	function createData(): void {
		# Todo: use static::$class property annotations for fakes
		$oTbl = $this->oDb->table(static::$table);
		$aField = (static::$class)::$aField;
		$oFaker = \Faker\Factory::create();

		for ($id = 1; $id <= 81; $id++) {
			$oFaker->seed($id); # always the same fakes
			$aRow = ['id' => $id];
			foreach ($aField as $name => [$type, $func, $aArg]) {
				$aRow[$name] = $oFaker->$func(...$aArg);
			};
			$oTbl->insert($aRow);
		}
	}
}