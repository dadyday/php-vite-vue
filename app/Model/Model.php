<?php

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class Model {

	protected Explorer $oDb;

	public function __construct(Explorer $oDbExplorer) {
		$this->oDb = $oDbExplorer;
	}

	function create(string $name): Selection {
		try {
			$oTbl = $this->oDb->table($name);
			return $oTbl;
		}
		catch (\Throwable $e) {}

		$oTbl = $this->createTable($name);
		if (!count($oTbl)) {
			$this->createData($oTbl);
		}
		return $oTbl;
	}

	function createTable(string $name): Selection {
		$this->oDb->query("
			CREATE TABLE IF NOT EXISTS ?name (
				id INTEGER PRIMARY KEY,
				name TEXT NOT NULL,
				email TEXT NOT NULL UNIQUE
			)
		", $name);
		return $this->oDb->table($name);
	}

	function createData(Selection $oTbl): void {
		$oFaker = \Faker\Factory::create();
		for ($id = 1; $id <= 81; $id++) {
			$oFaker->seed($id); # always the same fakes
			$aRow = [
				'id' => $id,
				'name' => $oFaker->name(),
				'email' => $oFaker->email(),
			];
			$oTbl->insert($aRow);
		}
	}
}