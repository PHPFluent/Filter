# PHPFluent\Filter
[![Build Status](https://secure.travis-ci.org/PHPFluent/Filter.png)](http://travis-ci.org/PHPFluent/Filter)
[![Total Downloads](https://poser.pugx.org/phpfluent/filter/downloads.png)](https://packagist.org/packages/phpfluent/filter)
[![License](https://poser.pugx.org/phpfluent/filter/license.png)](https://packagist.org/packages/phpfluent/filter)
[![Latest Stable Version](https://poser.pugx.org/phpfluent/filter/v/stable.png)](https://packagist.org/packages/phpfluent/filter)
[![Latest Unstable Version](https://poser.pugx.org/phpfluent/filter/v/unstable.png)](https://packagist.org/packages/phpfluent/filter)

Provider a better API to handle Zend filters.

## Installation

Package is available on [Packagist](https://packagist.org/packages/phpfluent/filter), you can install it
using [Composer](http://getcomposer.org).

```bash
composer require phpfluent/filter
```

## Usage

The static API was inspired on [Respect\Validation](https://github.com/Respect/Validation).

### Namespace Import

_PHPFluent\Filter_ is namespaced, but you can make your life easier by importing a single class into your context:

```php
use PHPFluent\Filter\Builder as f;
```

### Calling a filter

```php
f::stringToUpper()->filter('phpfluent'); // returns: 'PHPFLUENT'
```

### Calling multiple filters

```php
f::stringToUpper()
 ->stringTrim()
 ->filter('filter    '); // returns 'PHPFLUENT'
```

### Calling native PHP functions

```php
f::json_encode(JSON_PRETTY_PRINT)
 ->filter(array('key' => 'value')); // returns: '{"key": "value"}'
```

### Non-static API

You also can simply create an instance of `PHPFluent\Filter\Builder`.

```php
$builder = new PHPFluent\Filter\Builder();
$builder->ucfirst();
$builder->str_pad(10, '-');
$builder->filter('filter'); // returns: 'Filter----'
```

### Calling Builder class

`PHPFluent\Filter\Builder` implements `__invoke()` method, so you can do like:

```php
$builder('filter'); // returns: 'Filter----'
```

### Custom filters

You can use your own Zend filters.

```php
f::myFilter();
```

For that purpose we provide a way to add your own namespaces/prefixes:

```php
f::getDefaultFactory()->appendPrefix('My\\Filter\\Prefix');
```

So, in the example above `v::myFilter()` will call `My\Filter\PrefixMyFilter`.

### Filter factory

To create the filters by its name we use our Factory; there are two ways to change the Factory to be used.

#### Static calls

```php
$factory = new PHPFluent\Filter\Factory();
$factory->prependPrefix('My\\Zend\\Filters\\');

PHPFluent\Filter\Builder::setDefaultFactory($factory);
```

In the example above the defined factory will be used for all static calls.

#### Non-static calls

```php
$factory = new PHPFluent\Filter\Factory();
$factory->prependPrefix('My\\Zend\\Filters\\');

$builder = new PHPFluent\Filter\Builder($factory);
```

In the example above the defined factory will be used only for the `$builder` instance variable.

As you could note, the factory instance if optional, so, when you did defined a factory for the builder object it will
use the default one, defined on `getDefaultFactory()`.
