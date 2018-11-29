# 9pay Wallet
9pay Wallet is a library connected to the payment system through **9pay wallet** for merchants

## Requirements
- PHP >= 5.3

## Getting started

### Installation
Use [Composer](https://getcomposer.org/) to install
Just add the packagist dependecy:
```javascript  
    "require": {
        // ...
        "9pay/wallet": ">=1.0.0"
    }	
```
Or, if you want to get it directly from github, adding this to your composer.json should be enough:
```javascript
    "require": {
    	// ...
        "9pay/wallet": "dev-master"
    }
```
If you don't have composer.json file, use this:
```bash
    composer require 9pay/wallet
```
### Usage

#### Config
The support library uses two types of config files or arrays.
- File config:
 ```php
    $path = '/app/config';
``` 

You need to create config file with name is environment you will use. Examples of files with application configuration options:

sand.php
```php
return array(
    'url'     => 'https://example.com',
    'api_key' => 'api_key',
);
```

production.php
```php
return array(
    'url'     => 'https://example.com',
    'api_key' => 'api_key',
);
```
- Array config:

You can use an array config direct. Example:
 ```php
$config = array(
    'url'     => 'https://example.com',
    'api_key' => 'api_key',
);
```

#### Method
- create: create a payment in 9pay wallet
- query: get info a payment in 9pay wallet

#### Example Code:
```php
use Ninepay\Api\Wallet;

$config = array(
    'url'     => 'https://example.com',
    'api_key' => 'api_key',
);

$wallet = new Wallet($config);

$attr = array(
    'payment_no'  => '123456',
    'amount'      => '10000',
    'description' => 'This is test order',
    'return_url'  => 'https://example.com'
);

$res = $wallet->create($attr);
```

If you use array config, you don't set environment. If you use file config, you can set environment to use by:
```php
  $wallet = new Wallet($path, 'sand');
```
If you don't set environment, library will get `sand` is default.

## License
[MIT](https://choosealicense.com/licenses/mit/)
