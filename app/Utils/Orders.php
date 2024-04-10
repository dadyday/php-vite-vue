<?php

namespace App\Utils;

use Nette\Database\Table\Selection;
use Nette\SmartObject;
use Nette\Utils\Strings;

class Order {
	use SmartObject;

	public ?string $field = null;
	public bool $desc = false;

	function initParam(string $param): void {
		$aMatch = Strings::match($param, '~^(!?)(\w+)$~');
		[, $desc, $field] = $aMatch;

		$this->field = $field;
		$this->desc = !!$desc;
	}

	function addCriteria(Selection $oSelection): void {
		if ($this->field) $oSelection->order("?order", [ $this->field => !$this->desc ]);
	}
}


class Orders {

	/** @var array<Order> */
	protected array $aItem = [];

	function __construct(?string $param = null) {
		if ($param) $this->initByParam($param);
	}

	function initByParam(string $param): void {
		$aMatches = Strings::matchAll($param, '~[^,;]+~');
		foreach ($aMatches as [$entry]) {
			$oItem = new Order();
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
			$aResult[] = ($oItem->desc ? '!' : '') . $oItem->field;
		}
		return join(';', $aResult) ?: null;
	}

	public function getResponseData(): ?array {
		$aResult = [];
		foreach ($this->aItem as $oItem) {
			$aResult[] = (object) ['key' => $oItem->field, 'order' => $oItem->desc ? 'desc' : 'asc'];
		}
		return $aResult ?: null;
	}

}