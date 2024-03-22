<?php

declare(strict_types=1);

namespace App\Presenters;


use Nette\Database\Table\Selection;
use Nette\Utils\Paginator;

final class MainPresenter extends BasePresenter {


	/** @var Selection @inject */
	public Selection $oEntities;


	public function renderApp(): void {
		//$this->render();
	}

	public function renderDefault(): void {
		$this->render('404', [
			'name' => 'Universe',
			'frameworks' => [
				'Nette',
				'Vue',
				'Inertia',
			],
		]);
	}

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
}
