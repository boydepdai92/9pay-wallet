<?php

namespace Ninepay\Api;

use Ninepay\Api\Abstracts\Api;
use Ninepay\Api\Contracts\IWallet;
use Ninepay\Config\Config;
use Ninepay\Config\Loader;

class Wallet extends Api implements IWallet
{
	const URL_WALLET = '/v2.1/merchant/payments';

	private $api;

	private $api_key;

	/**
	 * @param $path
	 * @param $defaultEnvironment
	 * @throws \Exception
	 */
	private function processConfigFile($path, $defaultEnvironment)
	{
		$loader = new Loader($path);

		$config = new Config($loader, $defaultEnvironment);

		if ($config->has('url')) {
			$this->api = $config->get('url');
		} else {
			throw new \Exception('Url is missing');
		}

		if ($config->has('api_key')) {
			$this->api_key = $config->get('api_key');
		} else {
			throw new \Exception('Api key is missing');
		}
	}

	/**
	 * @param array $config
	 * @throws \Exception
	 */
	private function processConfigArray($config)
	{
		if (!empty($config['url'])) {
			$this->api = $config['url'];
		} else {
			throw new \Exception('Url is missing');
		}

		if (!empty($config['api_key'])) {
			$this->api_key = $config['api_key'];
		} else {
			throw new \Exception('Api key is missing');
		}
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
			$this->processConfigArray($path);
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

	public function create(array $attributes)
	{
		$header = $this->getHeader();

		$response = $this->call('POST', self::URL_WALLET, $header, $attributes);

		return $response;
	}

	public function query($id)
	{
		$header = $this->getHeader();

		$response = $this->call('GET', self::URL_WALLET . '/' . $id, $header, []);

		return $response;
	}
}