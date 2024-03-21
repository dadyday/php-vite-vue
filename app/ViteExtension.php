<?php

namespace App;

use Latte;

class ViteExtension extends Latte\Extension {

	protected string $wwwDir;
	protected string $distPath = '/dist';
	protected string $viteHost;

	protected ?bool $devMode = null;
	protected ?array $aManifest = null;

	public function __construct(string $wwwDir, string $distPath, string $viteHost) {
		$this->wwwDir = $wwwDir;
		$this->distPath = $distPath;
		$this->viteHost = $viteHost.$distPath;
		$this->checkDev();
	}

	protected function checkDev(): void {
		if (!is_null($this->devMode)) return;

		$handle = curl_init($this->viteHost . '/');
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_NOBODY, true);

		curl_exec($handle);
		$error = curl_errno($handle);
		# bdump([$this->viteHost, $error, curl_error($handle)]);
		curl_close($handle);

		$this->devMode = !$error;
	}

	public function getFunctions(): array {
		return [
			'vite' => [$this, 'vite'],
		];
	}

	public function vite(string $entry): string {

		return join("\n", [
			$this->jsTags($entry),
			$this->jsPreloadImports($entry),
			$this->cssTags($entry),
		]);
	}

	function jsTags(string $entry): string {
		$url = $this->devMode ? $this->viteHost . '/' . $entry : $this->assetUrl($entry);
		if (!$url) return '';

		$aReturn = [];
		if ($this->devMode) {
			$aReturn[] = '<script type="module" src="' . $this->viteHost . '/@vite/client"></script>';
		}
		$aReturn[] = '<script type="module" src="' . $url . '"></script>';
		return join("\n", $aReturn);
	}

	function assetUrl(string $entry): string {
		return $this->distPath . '/' . $this->getManifest($entry, 'file') ?? '';
	}

	protected function getManifest(string $entry, string $part = null): null|array|string {
		if (is_null($this->aManifest)) {
			$content = file_get_contents($this->wwwDir . $this->distPath . '/.vite/manifest.json');
			$this->aManifest = json_decode($content, true);
		}
		$aEntry = $this->aManifest[$entry] ?? [];
		return !$part ? $aEntry : $aEntry[$part] ?? null;
	}

	function jsPreloadImports(string $entry): string {
		if ($this->devMode) return '';

		$aReturn = [];
		foreach ($this->importsUrls($entry) as $url) {
			$aReturn[] = '<link rel="modulepreload" href="' . $url . '">';
		}
		return join("\n", $aReturn);
	}

	function importsUrls(string $entry): array {
		$aEntry = $this->getManifest($entry, 'imports') ?? [];

		$aUrl = [];
		foreach ($aEntry as $import) {
			$file = $this->getManifest($import, 'file');
			if ($file) $aUrl[] = $this->distPath . '/' . $file;
		}
		return $aUrl;
	}

	function cssTags(string $entry): string {
		if ($this->devMode) return '';

		$aReturn = [];
		foreach ($this->cssUrls($entry) as $url) {
			$aReturn[] = '<link rel="stylesheet" href="' . $url . '">';
		}
		return join("\n", $aReturn);
	}

	function cssUrls(string $entry): array {
		$aEntry = $this->getManifest($entry, 'css') ?? [];

		$aUrl = [];
		foreach ($aEntry as $file) {
			$aUrl[] = $this->distPath . '/' . $file;
		}
		return $aUrl;
	}

}

