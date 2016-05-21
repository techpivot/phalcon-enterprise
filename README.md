# Phalcon Enterprise

[![Scrutinizer](https://img.shields.io/scrutinizer/g/techpivot/phalcon-enterprise.svg?maxAge=2592000&label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/techpivot/phalcon-enterprise)
[![Latest Version](https://img.shields.io/packagist/v/techpivot/phalcon-enterprise.svg?style=flat-square)](https://packagist.org/packages/techpivot/phalcon-enterprise)
[![Total Downloads](https://img.shields.io/packagist/dt/techpivot/phalcon-enterprise.svg?style=flat-square)](https://packagist.org/packages/techpivot/phalcon-enterprise)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/techpivot/phalcon-enterprise/master/LICENSE)

techpivot/phalcon-enterprise is a set of tools, plugins, and components that provide extended enterprise 
functionality for the Phalcon PHP framework. 


## Installation via Composer

1. Add the `techpivot/phalcon-enterprise` repository into the **require** section of your `composer.json` as follows:

  ```json
  "require": {
      "techpivot/phalcon-enterprise": "~1.0"
  }
```
  
2. Run the `composer update` or `composer install` as necessary for your project.
3. Ensure your Phalcon application loader includes the composer autoloader. For example:
  ```php
use Phalcon\Loader;

$loader = Loader();
$loader->registerNamespaces([
    // Custom namespaces
]);
$loader->register();

// Composer autoload
require_once 'vendor/autoload.php';
```
