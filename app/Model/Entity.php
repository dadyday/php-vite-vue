<?php

namespace App\Model;

use Nette\Database\Table\ActiveRow;
use Nette\MemberAccessException;
use Nette\SmartObject;
use Nette\Utils\ObjectHelpers;
use Nette\Utils\Reflection;

abstract class Entity {
	use SmartObject {
		SmartObject::__get as __smartGet;
	}

	public function __construct() {
	}

	public function setData(ActiveRow $oRow): void {
		$oReflClass = new \ReflectionClass(static::class);
		$filter = \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC;
		foreach ($oReflClass->getProperties($filter) as $oReflProp) {
			$name = $oReflProp->getName();
			$this->$name = $oRow->$name;
		}
	}

	public function __get(string $name) {
		try {
			return $this->__smartGet($name);
		}
		catch (MemberAccessException $e) {}
		return $this->oRow->$name;
	}

	public function getData(array $aProp = null): object {
		$oData = new \stdClass();
		foreach ($aProp as $name) {
			$oData->$name = $this->$name;
		}
		return $oData;
	}

}