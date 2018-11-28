<?php

namespace Ninepay\Api\Contracts;

interface IApi
{
	public function call($method, $path, array $header, array $param);
}