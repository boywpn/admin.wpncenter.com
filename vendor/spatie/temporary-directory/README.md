# Quickly create, use and delete temporary directories

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/temporary-directory.svg?style=flat-square)](https://packagist.org/packages/spatie/temporary-directory)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/temporary-directory/master.svg?style=flat-square)](https://travis-ci.org/spatie/temporary-directory)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/temporary-directory.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/temporary-directory)
[![StyleCI](https://styleci.io/repos/80403728/shield?branch=master)](https://styleci.io/repos/80403728)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/temporary-directory.svg?style=flat-square)](https://packagist.org/packages/spatie/temporary-directory)

This package allows you to quickly create, use and delete a temporary directory in the system's temporary directory.

Here's a quick example on how to create a temporary directory and delete it:

```php
use Spatie\TemporaryDirectory\TemporaryDirectory;

$temporaryDirectory = (new TemporaryDirectory())->create();

// Get a path inside the temporary directory
$temporaryDirectory->path('temporaryfile.txt');

// Delete the temporary directory and all the files inside it
$temporaryDirectory->delete();
```

## Installation

You can install the package via composer:

```bash
composer require spatie/temporary-directory
```

## Usage

### Creating a temporary directory

To create a temporary directory simply call the `create` method on a `TemporaryDirectory` object. By default the temporary directory will be created in a timestamped directory in your system's temporary directory (usually `/tmp`).

```php
(new TemporaryDirectory())->create();
```

### Naming your temporary directory

If you want to use a custom name for your temporary directory instead of the timestamp call the `name` method with a string `$name` argument before the `create` method.

```php
(new TemporaryDirectory())
   ->name($name)
   ->create();
```

By default an exception will be thrown if a directory already exists with the given argument. You can override this behaviour by calling the `force` method in combination with the `name` method.

```php
(new TemporaryDirectory())
   ->name($name)
   ->force()
   ->create();
```

### Setting a custom location for a temporary directory

You can set a custom location in which your temporary directory will be created by passing a string `$location` argument to the `TemporaryDirectory` constructor.

```php
(new TemporaryDirectory($location))
   ->create();
```

Optionally you can call the `location` method with a `$location` argument.

```php
(new TemporaryDirectory())
   ->location($location)
   ->create();
```

### Determining paths within the temporary directory

You can use the `path` method to determine the full path to a file or directory in the temporary directory:

```php
$temporaryDirectory = (new TemporaryDirectory())->create();
$temporaryDirectory->path('dumps/datadump.dat'); // return  /tmp/1485941876276/dumps/datadump.dat
```

### Emptying a temporary directory

Use the `empty` method to delete all the files inside the temporary directory.

```php
$temporaryDirectory->empty();
```

### Deleting a temporary directory

Once you're done processing your temporary data you can delete the entire temporary directory using the `delete` method. All files inside of it will be deleted.

```php
$temporaryDirectory->delete();
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Alex Vanderbist](https://github.com/AlexVanderbist)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
