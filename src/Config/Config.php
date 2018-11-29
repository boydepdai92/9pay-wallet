<?php

namespace Ninepay\Config;

use Ninepay\Config\Contracts\IConfigLoader;

class Config
{
	/**
	 * @var string
	 */
	protected $settings;

	protected $environments;

	public function __construct(IConfigLoader $loader, $defaultEnvironment = 'sand')
	{
		$this->settings = $loader->fetch();

		$this->environments = $defaultEnvironment;
	}

	/**
	 * @param string $key
	 * @return string|array|null
	 */

	public function get($key)
	{
		return $this->getValue($key);
	}

	/**
	 * Check if key have value
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return ($this->getValue($key) !== null) ? true : false;
	}

	private function getValue($key)
	{
		return ($this->settings[$this->environments][$key]) ? $this->settings[$this->environments][$key] : null;
	}
}