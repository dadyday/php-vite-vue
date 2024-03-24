<?php

declare(strict_types=1);

namespace App\Presenters;

use PL\NetteInertia\InertiaPresenter;


class BasePresenter extends InertiaPresenter {

	protected ?string $component = null;
	protected array $aProp = [];
	protected ?string $link = null;

	function startup(): void {
		$view = $this->getParameter('view');
		if ($view) $this->setView($view);
		parent::startup();
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
		bdump($this->__debugInfo());

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
			'template' => $this->template->getFile(),
			'component' => $this->component,
			'link' => $this->link,
			'props' => $this->aProp,
			'this' => $this,
		];
	}


}
