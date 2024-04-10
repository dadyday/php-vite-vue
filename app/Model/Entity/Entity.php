<?php

namespace App\Model\Entity;


use Nette\Utils\DateTime;

/**
 * @property-read bool $active
 */
class Entity extends \App\Model\Entity {

	protected string $name;
	protected string $state;
	protected DateTime $created;

	function isActive(): bool {
		return $this->state !== 'closed';
	}

}