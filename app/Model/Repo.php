<?php

namespace App\Model;

use App\Utils\Fields;
use App\Utils\Filter;
use App\Utils\Filters;
use App\Utils\Order;
use App\Utils\Orders;
use Nette\Caching\Storage;
use Nette\Database\Conventions;
use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Utils\Paginator;
use Traversable;

abstract class Repo implements \IteratorAggregate, \Countable {

	static string $table;
	static string $class;
	static array $aFakeField = [];


	static function getFieldType($type): string {
		static $aTypeMap = [
			'string' => 'TEXT',
			'int' => 'INTEGER',
			'bool' => 'INTEGER',
			'Nette\Utils\DateTime' => 'DATETIME',
		];
		return $aTypeMap[$type];
	}

	static function getFields(): array {


		$aResult = [];
		$oReflClass = new \ReflectionClass(static::$class);
		foreach ($oReflClass->getProperties() as $oReflProp) {
			if ($oReflProp->isPublic() || $oReflProp->isProtected()) {
				$type = $oReflProp->getType()->getName();
				$aResult[$oReflProp->name] = static::getFieldType($type);
			}
		}
		return $aResult;
	}


	protected Selection $oTable;

	public function __construct(
		protected Explorer $oDb,
		Conventions $oConventions,
		?Storage $oCacheStorage = null
	) {
		$this->oTable = new Selection($oDb, $oConventions, static::$table, $oCacheStorage);
	}

	public function generateFakeData(bool $force = false): self {
		if ($force) $this->dropTable();
		$this->createTable();
		if (!count($this)) {
			$this->generateData();
		}
		$this->oTable->select('*');
		return $this;
	}

	function dropTable(): self  {
		$this->oDb->query("DROP TABLE IF EXISTS ?name", static::$table);
		return $this;
	}

	function createTable(): self {
		$sql = '';
		$aField = static::getFields();
		foreach ($aField as $name => $type) {
			$sql .= ",\n  $name $type";
		}

		$this->oDb->query("
			CREATE TABLE IF NOT EXISTS ?name (
				id INTEGER PRIMARY KEY $sql
			)
		", static::$table);
		return $this;
	}

	function generateData(): self {
		# Todo: use static::$class property annotations for fakes
		$aFieldDef = static::$aFakeField;
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
		return $this;
	}

	public function addFields(Fields $oFields): self {
		$this->oTable->select('*');
		return $this;
	}

	public function addFilter(Filter $oFilter): self {
		$oFilter->addCriteria($this->oTable);
		return $this;
	}

	public function addFilters(Filters $oFilters): self {
		$oFilters->addCriteria($this->oTable);
		return $this;
	}

	public function addOrder(Order $oOrder): self {
		$oOrder->addCriteria($this->oTable);
		return $this;
	}

	public function addOrders(Orders $oOrders): self {
		$oOrders->addCriteria($this->oTable);
		return $this;
	}

	public function addPagination(Paginator $oPaginator): self {
		$oPaginator->setItemCount($this->count());
		$this->oTable->limit($oPaginator->itemsPerPage, $oPaginator->offset);
		return $this;
	}



	public function count(): int {
		return $this->oTable->count();
	}

	public function getIterator(): Traversable {
		return $this->fetch();
	}

	public function fetch(): \Generator {
		foreach ($this->oTable as $oRow) {
			$oEntity = new static::$class();
			$oEntity->setData($oRow);
			yield $oEntity;
		}
	}

	public function fetchData(array $aProp = null): array {
		$aResult = [];
		foreach ($this as $oEntity) {
			$aResult[] = $oEntity->getData($aProp);
		}
		return $aResult;
	}

}