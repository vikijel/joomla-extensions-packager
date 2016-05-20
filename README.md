#   Joomla Extensions Packager *(Alpha)*
PHP Library for generating all-in-one install packages (*.zip) with multiple Joomla! extensions.

-   Package: **vikijel/joomla-extensions-packager**
-   Author: [Viktor Jelínek (VikiJel)](http://www.vikijel.cz), *<vikijel@gmail.com>*
-   License: [The MIT License (MIT)](LICENSE.txt)
-   Copyright: (c) 2016 Viktor Jelínek
-   [GitHub](https://github.com/vikijel/joomla-extensions-packager), [Packagist](https://packagist.org/packages/vikijel/joomla-extensions-packager)

##  Installation via Composer
Run this command in your project directory: 
```
composer require vikijel/joomla-extensions-packager
```

*You need to have [Composer](https://getcomposer.org/) installed for above command to work*

##  Usage - Basic example

```php
use VikiJel\JoomlaExtensionsPackager\Package;
use VikiJel\JoomlaExtensionsPackager\Packager;

try
{
	$path = Packager::pack(
		Package::create('Something All-In-One')
		       ->addExtension('com_test', '/path/to/com_test.zip')
		       ->addExtension('mod_test', '/path/to/mod_test.zip', 'module', 'site')
		       ->addExtension('plg_system_test', '/path/to/plg_system_test.zip', 'plugin', null, 'system')
	);
	
	echo 'Path to created package is: ' . $path;
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}
```

##  Usage - Advanced example

```php
use VikiJel\JoomlaExtensionsPackager\Package;
use VikiJel\JoomlaExtensionsPackager\Packager;
use VikiJel\JoomlaExtensionsPackager\Extension;

try
{
	$path = Packager::pack(
		Package::create('Something All-in-one')
		       ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://www.vikijel.cz')
		       ->setVersion('1.2.3')
		       ->setDescription('This is something...')
		       ->setLicense('GPL')
		       ->setCreationDate('2016-05-20')
		       ->setPkgVersion('3.2')
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
	);
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}
```
