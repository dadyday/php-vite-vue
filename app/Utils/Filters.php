<?php

namespace App\Utils;

use App\Model\Model;
use Nette\Database\Table\Selection;
use Nette\SmartObject;
use Nette\Utils\Strings;

class Filter {
	use SmartObject;

	public string $field;
	public string $operation;
	public mixed $value;

	function initParam(string $param): void {
		$aMatch = Strings::match($param, '~^(\w+)(!?(?::|\~|>:|>|<:|<))(.*)$~');
		[, $field, $op, $value] = $aMatch;

		$this->field = $field;
		$this->operation = $op;
		$this->initValue($value);
	}

	function initValue(string $value): void {
		$this->value = $value;
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
			$oSelection->where("?name = ?", $oItem->field, $oItem->value);
		}
	}

	public function getParamString(): ?string {
		$aResult = [];
		foreach ($this->aItem as $oItem) {
			$aResult[] = $oItem->field . $oItem->operation . $oItem->value;
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