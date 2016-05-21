#   Joomla! Extensions Packager&nbsp;&nbsp;&nbsp;&nbsp;[![Build Status](https://travis-ci.org/vikijel/joomla-extensions-packager.svg?branch=master)](https://travis-ci.org/vikijel/joomla-extensions-packager)
PHP Library for generating All-In-One install packages (*.zip) with multiple extensions for Joomla! CMS

-   Packs given extensions, languages, scriptfile etc. together with auto-generated install xml manifest into a ZIP archive ready for installation
-   Created packages are native extension installation packages of type 'package'
-   Created packages are compatible with Joomla! 2.5 and newer

##  About
-   Package: **vikijel/joomla-extensions-packager**
-   Source: [GitHub](https://github.com/vikijel/joomla-extensions-packager), [Packagist](https://packagist.org/packages/vikijel/joomla-extensions-packager)
-   Author: [Viktor Jelínek (VikiJel)](http://www.vikijel.cz), *<vikijel@gmail.com>*
-   License: [The MIT License (MIT)](LICENSE.txt)
-   Copyright: (c) 2016 Viktor Jelínek

##  Requirements
-   PHP 5.6 or newer
-   *For more info about PHP versions compatibility see [PHPUnit results at Travis](https://travis-ci.org/vikijel/joomla-extensions-packager)*

##  Installation via Composer
Run this command inside your project directory *(Your project dir is later referred to as `repository`)*: 
```
composer require vikijel/joomla-extensions-packager
```

*You need to have [Composer](https://getcomposer.org/) installed for above command to work*

##  Inclusion to project
-   *If you are using Composer in your project, the `vendor/autoload.php` should be already required in your project*
-   *`repository` = your project directory (where you ran `composer require`)*

```php
require_once '/path/to/repository/vendor/autoload.php'; 
```

##  Usage  - Basic
-   Source: [examples/usage_basic.php](examples/usage_basic.php)

```php
use VikiJel\JoomlaExtensionsPackager\Package;

try
{
	$path = Package::create('Something All-In-One')
	               ->addExtension('com_test', '/path/to/com_test.zip')
	               ->addExtension('mod_test', '/path/to/mod_test.zip', 'module', 'site')
	               ->addExtension('plg_system_test', '/path/to/plg_system_test.zip', 'plugin', null, 'system')
	               ->pack();

	echo 'Path to created package is: ' . $path; //Path to created package is: /path/to/repository/out/pkg_something_all_in_one-1.0.0.zip
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}
```

##  Usage - Advanced
-   Source: [examples/usage_advanced.php](examples/usage_advanced.php)
-   *More information can be found directly inside [Package](src/Package.php) class and other classes in form of php-doc comments*

```php
use VikiJel\JoomlaExtensionsPackager\Package;
use VikiJel\JoomlaExtensionsPackager\Extension;

try
{
	$path = Package::create('Something Else All-in-one')
	               ->setAuthor('Your Name', 'your.email@example.com', 'http://your.domain.example.com')
	               ->setVersion('1.2.3')
	               ->setDescription('This is something else...')
	               ->setLicense('GPL')
	               ->setCreationDate('2016-05-21')
	               ->setPkgVersion('2.5')
	               ->setUrl('http://url.com')
	               ->setScriptfile('/path/to/script.php')
	               ->addExtension('com_test', '/path/to/com_test.zip')
	               ->addExtension('mod_test', '/path/to/mod_test.zip', 'module', 'site')
	               ->addExtensionInstance(
		               Extension::create('plg_system_test', '/path/to/plg_system_test.zip', 'plugin')->setGroup('system')
	               )
	               ->addExtension('tpl_test', '/path/to/tpl_test.zip', 'template', 'admin')
	               ->addExtension('lib_test', '/path/to/lib_test.zip', 'library')
	               ->addExtension('lng_test', '/path/to/lng_test.zip', 'language', 'site')
	               ->addExtensionInstance(
		               Extension::create('file_test', '/path/to/file_test.zip', 'file')
	               )
	               ->pack('/path/to/custom_out_dir');

	echo 'Path to created package is: ' . $path; //Path to created package is: /path/to/custom_out_dir/pkg_something_else_all_in_one-1.2.3.zip
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}
```

--- 

*This library is not affiliated with or endorsed by the Joomla! Project or Open Source Matters. The Joomla!® name and logo is used under a limited license granted by Open Source Matters, the trademark holder in the United States and other countries.*
