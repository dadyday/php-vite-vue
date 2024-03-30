<?php

namespace App\Model\Entity;


use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;

/**
 * @property-read bool $active
 */
class Entity extends ActiveRow {

	static $aField = [
		'name' => ['text', 'name', []],
		'state' => ['text', 'randomElement', [['open', 'waiting', 'closed']]],
	];

	protected int $id;
	protected string $name;
	protected string $state;

	function isActive() {
		return !empty($this->state);
	}
}