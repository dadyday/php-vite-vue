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
	}

	function generateFakeData(bool $force = false) {
		if ($force) $this->dropTable();
		$this->createTable();
		if (!count($this)) {
			$this->generateData();
		}
		$this->select('*');
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

	function generateData(): void {
		# Todo: use static::$class property annotations for fakes
		$aFieldDef = (static::$class)::$aField;
		$oFaker = \Faker\Factory::create();

		$aRow = [];
		for ($id = 1; $id <= 81; $id++) {
			$oFaker->seed($id); # always the same fakes
			$aField = ['id' => $id];
			foreach ($aFieldDef as $name => [$type, $func, $aArg]) {
				if (is_string($func)) $func = fn($oFaker, ...$aArg) => $oFaker->$func(...$aArg);
				if (!is_callable($func)) throw new \LogicException("field '$name' has no valid generator func");
				$value = $func($oFaker, ...$aArg);
				if ($value instanceof \DateTime) $value = $value->format('Y-m-d');
				$aField[$name] = $value;
			};
			$aRow[] = $aField;
		}
		$this->oDb->query('INSERT INTO ?name ?', static::$table, $aRow);
	}
}