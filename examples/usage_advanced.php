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
	               ->setScriptfile('../tests/data/some_file.php')
	               ->addExtension('com_test', '../tests/data/some_file.zip')
	               ->addExtension('mod_test', '../tests/data/some_file.zip', 'module', 'site')
	               ->addExtensionInstance(
		               Extension::create('plg_system_test', '../tests/data/some_file.zip')
		                        ->setType('plugin')
		                        ->setGroup('system')
	               )
	               ->addExtension('tpl_test', '../tests/data/some_file.zip', 'template', 'admin')
	               ->addExtension('lib_test', '../tests/data/some_file.zip', 'library')
	               ->addExtension('lng_test', '../tests/data/some_file.zip', 'language', 'site')
	               ->addExtensionInstance(
		               Extension::create('file_test', '../tests/data/some_file.zip', 'file')
	               )
	               ->addLanguage('../tests/data/some_file.ini', 'cs-CZ')
	               ->addUpdateServer('http://updates1.example.com', 'My update server 1')
	               ->addUpdateServer('http://updates2.example.com', 'My update server 2', 'collection', 2)
	               ->pack('../out/custom', 'pkg_overridden_name.zip');

	echo 'Path to created package is ' . $path;
	//Outputs: Path to created package is /path/to/repository/out/custom/pkg_overridden_name.zip
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}