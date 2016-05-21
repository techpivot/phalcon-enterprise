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
      "techpivot/phalcon-enterprise": "~2.0"
  }
```
    Specify the `phalcon-enterprise` version _(as shown in the above example)_ that matches your installed Phalcon version:

    | Phalcon Version | Supported | Composer.json Version |
    |-----------------|-----------|-----------------------|
    | 1.3.x           | ✕        | -                     |
    | 2.0.x           | ✓         | "~2.0"                |
    | 2.1.x           | ✓         | "~2.1"                |

2. Run the `composer update` or `composer install` as necessary for your project.
3. Ensure your Phalcon application loader includes the composer autoloader. For example:
  ```php
use Phalcon\Loader;

$loader = new Loader();
$loader->registerNamespaces([
    // Custom namespaces
]);
$loader->register();

// Composer autoload
require_once 'vendor/autoload.php';
```


## Plugins, Adapters, & Extensions

### Dispatcher Plugins

#### CamelizeActionName

Typically URL routing uses hyphenated lowercase letters which do not directly map to equivalently
named controller actions. In order to fix this the action name is converted into camel case prior to
dispatching the request.

For example, if the original URL is: http://example.com/admin/products/show-latest-products,
and you want to name your action ‘showLatestProducts’, then this plugin will automatically handle
converting the uncamelized URL action such that the handler's action method will be properly
executed when dispatched.

> This will handle camelizing everything except not found routes in the dispatcher. The router 
will need to explicitly use the uncamelized form within `$router::notFound()`.
 
#### IndexRedirector
Ensures that any dispatched route that includes the explicit default index, typically "index", as the
action or controller is prohibited. The default behavior of allowing the explicit default index is
a by product of Phalcon's routing system that will allow explicitly matched controllers and actions.

Prohibiting this behavior is useful in ensuring accurate Search Engine Optimization as well as
providing a level of increased security as default routes can expose information about the underlying
system architecture.
 
#### NotFoundHandler
Automatically forwards the dispatcher to the specified error handler when the dispatched route
results in an invalid action or invalid handler.

## References

* [Phalcon PHP Framework](https://phalconphp.com)
* [Phalcon Incubator](https://github.com/phalcon/incubator)
* [TechPivot](https://www.techpivot.net)
