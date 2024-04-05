<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Model\Entities;
use App\Utils\Filters;
use App\Utils\Orders;
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



	public function renderEntities(
		int $page = null,
		int $perPage = null,
		string $search = null,
		string $filter = null,
		string $order = null,
		int $sleep = 0
	): void {

		$oEntities = $this->oEntities;
		$oEntities->generateFakeData(true);
		$aParam = [];

		// search
		if ($search) {
			$oEntities->where('name LIKE ?', "%$search%");
			$aParam['search'] = $search;
		}

		// filter
		$oFilters = new Filters($filter);
		$oFilters->addCriteria($oEntities);
		$aParam['filter'] = $oFilters->getParamString();

		// order
		$oOrders = new Orders($order);
		$oOrders->addCriteria($oEntities);
		$aParam['order'] = $oOrders->getParamString();


		// pagination
		static $defaultPage = 1, $defaultPerPage = 10;

		$page ??= $defaultPage;
		$perPage ??= $defaultPerPage;
		if ($perPage < 0) $perPage = 100;

		$oPaginator = (new Paginator())
			->setPage($page)
			->setItemsPerPage($perPage)
			->setItemCount($oEntities->count())
		;

		if ($page != $defaultPage) $aParam['page'] = $page;
		if ($perPage != $defaultPerPage) $aParam['perPage'] = $perPage;


		// response
		$aRow = $oEntities
			->select('id, name, state, created')
			->page($page, $perPage)
			->fetchAssoc('id')
		;
		$params = http_build_query($aParam);
		$params = $params ? '?'.$params : '';

		$this->render('Entities', '/entities'.$params, [
			'items' => array_values($aRow),
			'pagination' => [
				'page' => $oPaginator->page,
				'perPage' => $oPaginator->itemsPerPage,
				'pageCount' => $oPaginator->pageCount,

				'from' => $oPaginator->firstItemOnPage,
				'to' => $oPaginator->lastItemOnPage,
				'total' => $oPaginator->itemCount,
			],
			'search' => $search,
			'filters' => $oFilters->getResponseData(),
			'orders' => $oOrders->getResponseData(),
		]);

		// sleep
		$tm = microtime(true) + $sleep;
		$lastCd = 0;
		while (microtime(true) < $tm) {
			$fp = fopen("php://output", 'w');
			if (!$fp) break;
			fwrite($fp, '');
			fclose($fp);

			if(connection_status() != CONNECTION_NORMAL) {
				error_log('connection aborted');
				break;
			}

			usleep(100);
			$cd = round($tm - microtime(true));
			if ($cd != $lastCd) error_log("$cd");
			$lastCd = $cd;
		}
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
