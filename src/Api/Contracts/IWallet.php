<?php

namespace Ninepay\Api\Contracts;

interface IWallet
{
	public function create(array $attributes);

	public function query($id);
}