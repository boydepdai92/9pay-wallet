<?php

namespace Ninepay\Config;

use Ninepay\Config\Contracts\IConfigLoader;

use RecursiveIteratorIterator, RecursiveDirectoryIterator;

class Loader implements IConfigLoader
{
	/**
	 * Directory with the settings files
	 * @var string
	 */
	protected $path;

	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

	/**
	 * Load configuration settings
	 * @return array
	 * @throws \Exception
	 */
	public function fetch()
	{
		if (!$this->validateDirectory($this->path)) {
			throw new \Exception('Config path is not valid directory');
		}

		return $this->buildConfigData($this->path);
	}

	/**
	 * @param string $path
	 * Check if the given path is a directory with correct read permissions
	 * @return bool
	 */
	private function validateDirectory($path)
	{
		if (is_dir($path) && is_readable($path)) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $path
	 * Build settings from scanned config files
	 * @return array
	 */
	private function buildConfigData($path)
	{
		$configBuild = array();

		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS));

		foreach($iterator as $name => $item) {
			if (strstr($name, '.php')) {
				$envName = strtolower(substr($item->getFileName(), 0, strpos($item->getFileName(), '.')));

				$arraySettings = include $name;

				if (is_array($arraySettings)) {
					$configBuild[$envName] = $arraySettings;
				}
			}
		}

		return $configBuild;
	}
}