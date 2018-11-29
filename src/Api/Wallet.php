<?php

namespace Ninepay\Api;

use Ninepay\Api\Abstracts\Api;
use Ninepay\Api\Contracts\IWallet;
use Ninepay\Config\Config;
use Ninepay\Config\Loader;

class Wallet extends Api implements IWallet
{
	const URL_WALLET = '/merchant/payments';

	private $text = 'v';

	private $api;

	private $api_key;

	private $version;

	private function getValueFromKey($config, $key)
	{
		if (is_array($config)) {
			if (!empty($config[$key])) {
				$value = $config[$key];
			} else {
				throw new \Exception($key . ' is missing');
			}
		} else {
			if ($config->has($key)) {
				$value = $config->get($key);
			} else {
				throw new \Exception($key . ' is missing');
			}
		}

		return $value;
	}

	/**
	 * @param $config
	 * @throws \Exception
	 */
	private function setParam($config)
	{
		$this->api = $this->getValueFromKey($config, 'url');

		$this->api_key = $this->getValueFromKey($config, 'api_key');

		$this->version = $this->getValueFromKey($config, 'version');
	}

	/**
	 * @param $path
	 * @param $defaultEnvironment
	 * @throws \Exception
	 */
	private function processConfigFile($path, $defaultEnvironment)
	{
		$loader = new Loader($path);

		$config = new Config($loader, $defaultEnvironment);

		$this->setParam($config);
	}

	/**
	 * Wallet constructor.
	 * @param $path
	 * @param string $defaultEnvironment
	 * @throws \Exception
	 */
	public function __construct($path, $defaultEnvironment = 'sand')
	{
		if (is_array($path)) {
			$this->setParam($path);
		} else {
			$this->processConfigFile($path, $defaultEnvironment);
		}

		parent::__construct();
	}

	public function getUrl()
	{
		return rtrim($this->api, '/');
	}

	private function getHeader()
	{
		return array(
			'api_key' => $this->api_key
		);
	}

	private function buildPath($path)
	{
		return '/'. $this->text . $this->version . $path;
	}

	public function create(array $attributes)
	{
		$path = $this->buildPath(self::URL_WALLET);

		$header = $this->getHeader();

		$response = $this->call('POST', $path, $header, $attributes);

		return $response;
	}

	public function query($id)
	{
		$path = $this->buildPath(self::URL_WALLET . '/' . $id);

		$header = $this->getHeader();

		$response = $this->call('GET', $path, $header, []);

		return $response;
	}
}