# Phalcon Enterprise

[![Scrutinizer](https://img.shields.io/scrutinizer/g/techpivot/phalcon-enterprise.svg?maxAge=2592000&label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/techpivot/phalcon-enterprise)
[![Latest Version](https://img.shields.io/packagist/v/techpivot/phalcon-enterprise.svg?style=flat-square)](https://packagist.org/packages/techpivot/phalcon-enterprise)
[![Total Downloads](https://img.shields.io/packagist/dt/techpivot/phalcon-enterprise.svg?style=flat-square)](https://packagist.org/packages/techpivot/phalcon-enterprise)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/techpivot/phalcon-enterprise/master/LICENSE)

techpivot/phalcon-enterprise is a set of tools, plugins, and components that provide extended enterprise 
functionality for the Phalcon PHP framework. This project provides functionality similar to that of the 
[Phalcon Incubator](https://github.com/phalcon/incubator); however, all adapters and plugins are designed to run
in production with parity against the current stable and mainline Phalcon versions.


## Installation via Composer

1. Add the `techpivot/phalcon-enterprise` repository into the **require** section of your `composer.json` as follows:

  ```json
  "require": {
      "techpivot/phalcon-enterprise": "~2.1"
  }
```
    Specify the `phalcon-enterprise` version _(as shown in the above example)_ that matches your installed Phalcon version:

    | Phalcon Version | Supported | Composer.json Version |
    |-----------------|-----------|-----------------------|
    | 1.3.x           | ✕        | -                     |
    | 2.0.x           | ✓         | "~2.0"                |
    | 2.1.x           | ✓         | "~2.1"                |

2. Run the `composer update` or `composer install` as necessary for your project.
3. Include in your project loader using Composer autoloading or Phalcon's autoloader:

    * **Phalcon Autoloader**
        ```php
        use Phalcon\Loader;

        $loader = new Loader();
        $loader->registerNamespaces([
            // Your custom namespaces ...
    
            // Include TechPivot\Phalcon\Enterprise
            'TechPivot\Phalcon\Enterprise' => 'vendor/techpivot/phalcon-enterprise/src',
        ]);
        $loader->register();
        ```

    * **Composer Autoloader**
        ```php
        require_once 'vendor/autoload.php';
        ```

## Plugins, Adapters, & Extensions

### Dispatcher Plugins

#### AjaxRouteHandler

#### CamelizeActionName
 
#### IndexRedirector
 
#### NotFoundHandler


## References

* [Phalcon PHP Framework](https://phalconphp.com)
* [Phalcon Incubator](https://github.com/phalcon/incubator)
* [TechPivot](https://www.techpivot.net)
