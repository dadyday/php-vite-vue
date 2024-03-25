<?php

declare(strict_types=1);

namespace App\Presenters;


use Nette\Database\Table\Selection;
use Nette\Utils\Paginator;
use Nette\Security\AuthenticationException;

final class AppPresenter extends BasePresenter {

	public function actionMain(): void {
		$oUser = $this->getUser();
		if (!$oUser->isLoggedIn()) {
			$this->view('Login');
		}
		else {
			$this->view('Start');
		}
	}

	function share(array $aProp): array {
		$oIdentity = $this->getUser()->identity;
		$aProp = [...$aProp,
			'user' => [
				'name' => $oIdentity->getId(),
				'roles' => $oIdentity->getRoles(),
				...$oIdentity->getData(),
			]
		];
		return parent::share($aProp);
	}

	function actionLogin(): void {
		$oUser = $this->getUser();
		if ($oUser->isLoggedIn()) {
			$oUser->logout();
		}

		if (!$this->hasData()) {
			$this->view('Login');
			return;
		}

		try {
			$userId = $this->getData('userId');
			$password = $this->getData('password');

			if (!$userId || !$password) throw new AuthenticationException('user and/or password was empty');

			$oUser->login($userId, $password);
			$this->view('Start', [
				'lastLogin' => $oUser->identity->lastLogin ?? null
			]);
			$oUser->identity->lastLogin = date('Y-m-d H:i:s');
			$oUser->identity->triedLogin = ($oUser->identity->triedLogin ?? 0) +1;
		}
		catch (AuthenticationException $e) {
			bdump($e);
			$this->view('Login', [ 'error' => $e->getMessage() ]);
		}
	}

	public function actionDefault($view): void {
		$oUser = $this->getUser();
		if (!$oUser->isLoggedIn()) {
			$this->view('Login');
		}
	}

	function renderFoo() {
		$this->render('Foo', '/foo?bar', ['name' => 'foobar']);
	}

	function renderBar() {
		if (!$this->getUser()->isInRole('admin')) return $this->error('not allowed', 403);
		$this->render('Bar');
	}



/*
	public function renderEntities(int $page = null, string $search = null, int $sleep = 0): void {

		if ($search) $this->oEntities->where('name LIKE ?', "%$search%");

		$oPaginator = (new Paginator())
			->setPage($page ?? 1)
			->setItemsPerPage(10)
			->setItemCount(count($this->oEntities))
		;

		$aRow = $this->oEntities
			->select('id, field')
			->page($page ?? 1, 10)
			->fetchAssoc('id')
		;


		sleep($sleep);
		$this->render([
			'entities' => [
				'currentPage' => $oPaginator->page,
				'perPage' => $oPaginator->itemsPerPage,
				'firstPage' => $oPaginator->firstPage,
				'lastPage' => $oPaginator->lastPage,
				'pageCount' => $oPaginator->pageCount,

				'from' => $oPaginator->firstItemOnPage,
				'to' => $oPaginator->lastItemOnPage,
				'total' => $oPaginator->itemCount,

				'data' => array_values($aRow),
			],
			'filters' => [
				'search' => $this->getRequest()->getParameter('search'),
			],
			'time' => date('Y-m-d H:i:s'),
		], 'Entities');
	}

	public function renderAssistant(): void {
		$this->render([
			'hosts' => [
				'foo' => [ 'name' => 'Foo Host', 'host' => 'foo.example.com', 'available' => true],
				'bar' => [ 'name' => 'Bar Host', 'host' => 'bar.example.com', 'available' => true],
				'baz' => [ 'name' => 'Baz Host', 'host' => 'baz.example.com', 'available' => false],
			],
			'entities' => [
				'foo' => [ 'title' => 'Entity Foo', 'mode' => true, 'available' => true ],
				'bar' => [ 'title' => 'Entity Bar', 'mode' => false, 'available' => true ],
				'baz' => [ 'title' => 'Entity Baz', 'mode' => null, 'available' => false ],
				'qux' => [ 'title' => 'Entity Qux', 'mode' => null, 'available' => true ],
				# foo, bar, baz, qux, quux, corge, grault, garply, waldo, fred, plugh, xyzzy, thud.
			],
			'filters' => [
				'foo' => [ 'name' => 'Foo Filter' ],
				'bar' => [ 'name' => 'Bar Filter' ],
				'baz' => [ 'name' => 'Baz Filter' ],
			],
		], 'Assistant');
	}
*/
}
