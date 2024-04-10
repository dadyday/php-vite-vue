<?php

namespace App\Utils;

use Nette\Database\Table\Selection;
use Nette\SmartObject;
use Nette\Utils\Strings;

class Filter {
	use SmartObject;

	const
		OPERATION_EQUAL = ':',
		OPERATION_LIKE = '~',
		OPERATION_INLIST = ',',
		OPERATION_RANGE = '..';

	public ?string $field = null;
	public ?string $operation = null;
	public mixed $value = null;

	public function __construct(string $param = null) {
		if ($param) $this->initParam($param);
	}

	function initParam(string $param): void {
		$aMatch = Strings::match($param, '~^(\w+)([<>:\~]+)(.*)$~', unmatchedAsNull: true, utf8: true);
		[, $field, $op, $value] = $aMatch;

		$this->field = $field;
		$this->initOperation($op);
		$this->initValue($value);
	}

	function initOperation($op): void {
		switch ($op) {
			case ':':
				$this->operation = static::OPERATION_EQUAL;
				break;
			case '~':
				$this->operation = static::OPERATION_LIKE;
				break;
			default: throw new \RuntimeException("invalid filter operation '$op'");
		}
	}

	function initValue(string $value): void {
		$split = function(string $pattern, $limit = -1) use ($value) {
			$aResult = Strings::split($value, $pattern, limit: $limit, utf8: true);
			return count($aResult) >= 2 ? $aResult : false;
		};

		switch (true) {
			case $aValue = $split('~\s*\.\.\s*~', 2):
				$this->operation = static::OPERATION_RANGE;
				$this->value = $aValue;
				break;
			case $aValue = $split('~\s*,\s*~'):
				$this->operation = static::OPERATION_INLIST;
				$this->value = $aValue;
				break;
			# case $aMatch = Strings::match($value, '~^([\/\~\^])(.*)([\/\~\$])$~', utf8: true):
			# 	$this->operation = static::OPERATION_MATCH;
			# 	$this->value = ;
			# 	break;
			case Strings::match($value, '~%~', utf8: true):
				$this->operation = static::OPERATION_LIKE;
				$this->value = $value;
				break;
			default:
				$this->value = $value;
		}
	}

	public function addCriteria(Selection $oSelection): void {
		switch ($this->operation) {
			case null:
				break;
			case static::OPERATION_EQUAL:
				$oSelection->where("?name = ?", $this->field, $this->value);
				break;
			case static::OPERATION_RANGE:
				$oSelection->where("?name BETWEEN ? AND ?", $this->field, $this->value[0], $this->value[1]);
				break;
			case static::OPERATION_INLIST:
				$oSelection->where("?name IN ?", $this->field, $this->value);
				break;
			case static::OPERATION_LIKE:
				$oSelection->where("?name LIKE ?", $this->field, $this->value);
				break;
			default: throw new \LogicException("filter operation '$this->operation' not implemented");
		}


	}

	public function getParamString(): ?string {
		switch ($this->operation) {
			case null:
				return null;
			case static::OPERATION_RANGE:
				return $this->field . ':' . $this->value[0] . '..' . $this->value[1];
			case static::OPERATION_INLIST:
				return $this->field . ':' . join(',', $this->value);
			default:
				return $this->field . $this->operation . $this->value;
		}
	}
}


class Filters {

	/** @var array<Filter> */
	protected array $aItem = [];

	function __construct(?string $param = null) {
		if ($param) $this->initByParam($param);
	}

	function initByParam(string $param): void {
		$aMatches = Strings::matchAll($param, '~[^;]+~');
		foreach ($aMatches as [$entry]) {
			$oItem = new Filter();
			$oItem->initParam($entry);
			$this->aItem[] = $oItem;
		}
	}

	function addCriteria(Selection $oSelection): void {
		foreach ($this->aItem as $oItem) {
			$oItem->addCriteria($oSelection);
		}
	}

	public function getParamString(): ?string {
		$aResult = [];
		foreach ($this->aItem as $oItem) {
			$param = $oItem->getParamString();
			if ($param) $aResult[] = $param;
		}
		return join(';', $aResult) ?: null;
	}

	public function getResponseData(): ?object {
		$aResult = [];
		foreach ($this->aItem as $oItem) {
			$aResult[$oItem->field] = $oItem->value;
		}
		return $aResult ? (object) $aResult : null;
	}

}