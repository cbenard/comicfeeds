<?php

namespace Comicfeeds;

class StorageService
{
	private string $cacheDir = __DIR__ . '/../cache';

	public function save($filename, $contents)
	{
		$this->ensureDirectory();
		file_put_contents($this->cacheDir . '/' . $filename, $contents);
	}

	public function load($filename)
	{
		$newFilename = $this->cacheDir . '/' . $filename;
		if (!file_exists($newFilename)) {
			throw new \Exception("Sorry. That feed does not exist.");
		}
		$contents = file_get_contents($newFilename);

		return $contents;
	}

	private function ensureDirectory()
	{
		if (!is_dir($this->cacheDir)) {
			mkdir($this->cacheDir);
		}
	}
}
