#   Joomla Extensions Packager
Library for generating all-in-one install packages (*.zip) with multiple Joomla! extensions

-   Author: [Viktor Jelínek](http://www.vikijel.cz) *<vikijel@gmail.com>*
-   License: [The MIT License (MIT), Copyright (c) 2016 Viktor Jelínek](LICENSE.txt)
-   GitHub: [vikijel/joomla-extensions-packager](https://github.com/vikijel/joomla-extensions-packager)

##  UNDER DEVELOPMENT

##  Usage - Basic example

```php
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
```
