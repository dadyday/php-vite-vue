<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Model\Entities;
use Nette\Utils\Paginator;
use Nette\Security\AuthenticationException;

final class AppPresenter extends BasePresenter {

	public function __construct(
		private Entities $oEntities,
	) {
		parent::__construct();
	}

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
		$oIdentity = $this->getUser()->identity ?? null;
		if ($oIdentity) {
			$aProp = [
				...$aProp,
				'user' => [
					'name'  => $oIdentity->getId(),
					'roles' => $oIdentity->getRoles(),
					...$oIdentity->getData(),
				]
			];
		}
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
		$this->render('Foo', '/foo', ['name' => 'foobar']);
	}

	function renderBar($n) {
		if (!$this->getUser()->isInRole('admin')) return $this->error('not allowed', 403);
		$this->render('Bar', '/bar?'.$n);
	}



	public function renderEntities(int $page = null, string $search = null, int $sleep = 3): void {

		$oEntities = $this->oEntities;
		if ($search) $oEntities->where('name LIKE ?', "%$search%");

		$oPaginator = (new Paginator())
			->setPage($page ?? 1)
			->setItemsPerPage(5)
			->setItemCount($oEntities->count())
		;

		$aRow = $oEntities
			->select('id, name, state')
			->page($page ?? 1, 5)
			->fetchAssoc('id')
		;


		sleep($sleep);
		$this->render('Entities', '/entities?page='.($page ?? 1), [
			'items' => array_values($aRow),
			'pagination' => [
				'page' => $oPaginator->page,
				'perPage' => $oPaginator->itemsPerPage,
				'pageCount' => $oPaginator->pageCount,

				'from' => $oPaginator->firstItemOnPage,
				'to' => $oPaginator->lastItemOnPage,
				'total' => $oPaginator->itemCount,
			],
			'filters' => [
				'search' => $this->getRequest()->getParameter('search'),
			],
		]);
	}

	/*
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
