<?php
/**
 * Usage of JoomlaExtensionsPackager library - Basic Example
 * 
 * @author: Viktor Jelínek (VikiJel)
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use VikiJel\JoomlaExtensionsPackager\Package;

try
{
	$path = Package::create('Something All-In-One')
	               ->addExtension('com_test', '/path/to/com_test.zip')
	               ->addExtension('mod_test', '/path/to/mod_test.zip', 'module', 'site')
	               ->addExtension('plg_system_test', '/path/to/plg_system_test.zip', 'plugin', null, 'system')
	               ->pack();

	echo 'Path to created package is ' . $path; 
	//Outputs: Path to created package is: /path/to/repository/out/pkg_something_all_in_one-1.0.0.zip
}
catch (Exception $e)
{
	die('Failed to create package, error: ' . $e->getMessage());
}