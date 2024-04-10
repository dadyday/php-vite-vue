<?php

namespace App\Utils;

use Nette\Database\Table\Selection;
use Traversable;

class Fields implements \IteratorAggregate {

	public function __construct(protected array $aField) {}

	public function addCriteria(Selection $oSelection) {
		$fields = join(', ', $this->aField);
		$oSelection->select($fields);
	}

	public function getIterator(): Traversable {
		return new \ArrayIterator($this->aField);
	}
}