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

	private function useGuzzleHttp($method, $path, $header, $param)
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

		return array(
			'status' => $response->getStatusCode(),
			'body'   => json_decode($response->getBody(), true)
		);
	}

	private function useCurl($method, $path, $header, $param)
	{
		$url = $this->url . $path;

		$method = strtoupper($method);

		$field = http_build_query($param);

		$headers = array();

		foreach ($header as $key => $value) {
			$headers[] = $key . ': ' . $value;
		}

		if ($method == 'GET' && $field != '') {
			$url = $url . '?' . $field;
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_ENCODING , 'UTF-8');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $field);
		}

		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return array(
			'status' => $status,
			'body'   => json_decode($result, true)
		);
	}

	public function call($method, $path, array $header, array $param)
	{
		if (class_exists('\GuzzleHttp\Client')) {
			$response = $this->useGuzzleHttp($method, $path, $header, $param);
		} else {
			$response = $this->useCurl($method, $path, $header, $param);
		}

		return $response;
	}
}