<?php

declare(strict_types=1);

namespace App\Presenters;

use PL\NetteInertia\InertiaPresenter;


class BasePresenter extends InertiaPresenter {

	protected ?string $component = null;
	protected array $aProp = [];
	protected ?string $link = null;

	public function inertia(array $aProp = [], ?string $component = null, ?string $link = null) {
		$this->aProp = $aProp;
		$this->component = $component;
		$this->link = $link;
	}

	protected function share(array $aProp): array {
		return $aProp;
	}

	public function afterRender(): void {
		$aProp = $this->share($this->aProp);
		parent::inertia($aProp, $this->component, $this->link);
		parent::afterRender();
		$this->view = 'default';
	}

	protected function getAssetVersion(): string {
		return '1.0';
	}


}
