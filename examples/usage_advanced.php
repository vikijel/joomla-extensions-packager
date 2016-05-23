<?php
/**
 * Usage of JoomlaExtensionsPackager library - Advanced Example
 *
 * @author: Viktor Jelínek (VikiJel)
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

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
	               ->setMinJoomlaVersion('2.5')
	               ->setUrl('http://url.com')
	               ->setScriptfile('/path/to/script.php')
	               ->addExtension('com_test', '/path/to/com_test.zip')
	               ->addExtension('mod_test', '/path/to/mod_test.zip', 'module', 'site')
	               ->addExtensionInstance(
		               Extension::create('plg_system_test', '/path/to/plg_system_test.zip', 'plugin')
		                        ->setGroup('system')
	               )
	               ->addExtension('tpl_test', '/path/to/tpl_test.zip', 'template', 'admin')
	               ->addExtension('lib_test', '/path/to/lib_test.zip', 'library')
	               ->addExtension('lng_test', '/path/to/lng_test.zip', 'language', 'site')
	               ->addExtensionInstance(
		               Extension::create('file_test', '/path/to/file_test.zip', 'file')
	               )
	               ->addLanguage('/path/to/cs-CZ.pkg_something.ini', 'cs-CZ')
	               ->addUpdateServer('http://updates1.example.com', 'My update server 1')
	               ->addUpdateServer('http://updates2.example.com', 'My update server 2', 'collection', 2)
	               ->pack('/path/to/custom_out_dir', 'pkg_overridden_name.zip');

	echo 'Path to created package is ' . $path;
	//Outputs: Path to created package is /path/to/custom_out_dir/pkg_overridden_name.zip
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}