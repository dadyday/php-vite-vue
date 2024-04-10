<?php
namespace App\Model\Repo;


use App\Model\Entity;
use App\Model\Repo;

class Entities extends Repo {

	static string $table = 'entity';
	static string $class = Entity\Entity::class;

	static array $aFakeField = [
		'name' => ['text', 'name', []],
		'state' => ['text', 'randomElement', [['open', 'waiting', 'closed']]],
		'created' => ['date', 'dateTimeThisMonth', []],
	];
}