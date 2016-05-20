#   Joomla Extensions Packager
Library for generating all-in-one install packages (*.zip) with multiple Joomla! extensions

##  UNDER DEVELOPMENT

##  Basic Usage

```php
try
{
	$path = Packager::pack(
		Package::create('Something All-In-One')
		       ->addExtension('com_test', 'path/to/com_test.zip')
		       ->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site')
		       ->addExtension('plg_system_test', 'path/to/plg_system_test.zip', 'plugin', null, 'system')
	);
	
	echo 'Path to created package is: ' . $path;
}
catch (Exception $e)
{
	die($e->getMessage());
}
```

##  Old-School Usage

```php
try
{
	$package = new Package('Something All-In-One');
	
	$package->addExtension('com_test', 'path/to/com_test.zip');
	$package->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site');
	$package->addExtension('plg_system_test', 'path/to/plg_system_test.zip', 'plugin', null, 'system');
	
	$path = Packager::pack($package);
	
	echo 'Path to created package is: ' . $path;
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}
```

##  About
-   Author: [Viktor Jelínek](http://www.vikijel.cz) <vikijel@gmail.com>
-   License: [The MIT License (MIT), Copyright (c) 2016 Viktor Jelínek](LICENSE.txt)
