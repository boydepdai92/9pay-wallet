<?php

namespace Ninepay\Config\Contracts;

interface IConfigLoader
{
	/**
	 * @return mixed
	 */
	public function fetch();
}