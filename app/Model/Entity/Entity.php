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
		'created' => ['date', 'dateTimeThisMonth', []],
	];

	protected int $id;
	protected string $name;
	protected string $state;
	protected \DateTime $created;

	function isActive() {
		return !empty($this->state);
	}

	function getCreated() {
		return date('Y-m-d', $this->created);
	}
}