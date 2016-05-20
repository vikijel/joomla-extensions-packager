#   Joomla Extensions Packager
Library for generating all-in-one install packages (*.zip) with multiple Joomla! extensions

-   Author: [Viktor Jelínek](http://www.vikijel.cz) *<vikijel@gmail.com>*
-   License: [The MIT License (MIT), Copyright (c) 2016 Viktor Jelínek](LICENSE.txt)
-   GitHub: [vikijel/joomla-extensions-packager](https://github.com/vikijel/joomla-extensions-packager)

##  UNDER DEVELOPMENT

##  Basic Modern Usage example

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
	die('Failed to create package, error: ' . $e->getMessage());
}
```

##  Basic Old-School Usage example

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
