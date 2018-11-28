<?php

namespace Ninepay\Api\Abstracts;

use Ninepay\Api\Contracts\IApi;
use GuzzleHttp\Client;

abstract class Api implements IApi
{
	protected $url;

	public function __construct()
	{
		$this->setUrl();
	}

	abstract public function getUrl();

	private function setUrl()
	{
		$this->url = $this->getUrl();
	}

	public function call($method, $path, array $header, array $param)
	{
		$url = $this->url . $path;

		$method = strtoupper($method);

		$client = new Client();

		$data = array(
			'http_errors' => false,
			'verify'      => false,
			'headers'     => $header
		);

		if ($method == 'GET') {
			$data['query'] = $param;
		} else {
			$data['form_params'] = $param;
		}

		$response = $client->request($method, $url, $data);

		return $response;
	}
}