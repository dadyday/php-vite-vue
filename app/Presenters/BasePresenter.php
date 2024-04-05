<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use PL\NetteInertia\InertiaPresenter;


class BasePresenter extends InertiaPresenter {


	protected $oData = null;

	protected ?string $component = null;
	protected array $aProp = [];
	protected ?string $link = null;

	function startup(): void {
		$view = $this->getParameter('view');
		if ($view) $this->setView($view);
		parent::startup();
	}


	function isJsonRequest() {
		return $this->getHttpRequest()->getHeader('content-type') === 'application/json';
	}

	function hasData() {
		return $this->getHttpRequest()->isMethod('POST');
	}

	function initJsonRequest() {
		if ($this->isJsonRequest() && is_null($this->oData)) {
			$rawBody = $this->getHttpRequest()->getRawBody();
			try {
				$this->oData = $rawBody ? Json::decode($rawBody) : false;
			}
			catch (JsonException $e) {
				$this->oData = false;
			}
		}
	}

	function getData(?string $name = null, $default = null) {
		$this->initJsonRequest();
		return !$name ? $this->oData : $this->oData->$name ?? $default;
	}



	function view($view, $aProp = null): void {
		$this->setView($view);
		$this->render($view, $aProp ?? []);
	}

	function error(string $message = '', int $httpCode = Nette\Http\IResponse::S404_NOT_FOUND): void {
		$this->setView('error');
		$this->render('error', [
			'code' => $httpCode,
			'message' => $message,
		]);
	}


	public function render(...$aArg): void {
		foreach ($aArg as $arg) {
			if (is_array($arg)) {
				$this->aProp = array_merge($this->aProp, $arg);
			}
			else if (is_string($arg)) {
				if ($arg[0] === '/') $this->link = $arg;
				else $this->component = $arg;
			}
		}
	}

	protected function share(array $aProp): array {
		return $aProp;
	}

	public function afterRender(): void {
		if (!$this->component) {
			$this->render('Error', [
				'code' => 404,
				'message' => "View '$this->view' not found",
			]);
		}

		$this->aProp = $this->share($this->aProp);
		$this->view = 'default';
		bdump($this->__debugInfo(), '', [ 'depth' => 5, 'lazy' => false, 'collapsecount' => 20 ]);

		parent::inertia($this->aProp, $this->component, $this->link);
		parent::afterRender();
	}

	protected function getAssetVersion(): string {
		return '1.0';
	}

	function __debugInfo() {
		return [
			'presenter' => $this->name,
			'action' => $this->action,
			'view' => $this->view,
			'params' => $this->getParameters(),
			'data' => $this->getData(),
			'template' => $this->template->getFile(),
			'component' => $this->component,
			'link' => $this->link,
			'props' => $this->aProp,
			'this' => $this,
		];
	}


}
