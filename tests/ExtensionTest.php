<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
	static $name   = 'Plg system test';
	static $file   = 'C:\\wamp\\www\\SomeFile.zip';
	static $type   = 'Plugin';
	static $client = null;
	static $group  = 'SYSteM';

	public function testClassInstantiates()
	{
		$instance = new Extension(static::$name, static::$file, static::$type, static::$client, static::$group);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = new Extension(static::$name, static::$file, static::$type, static::$client, static::$group);

		$this->assertEquals('plg_system_test', $instance->getName());
		$this->assertEquals('C:' . DIRECTORY_SEPARATOR . 'wamp' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'SomeFile.zip', $instance->getFile());
		$this->assertEquals('plugin', $instance->getType());
		$this->assertEquals(null, $instance->getClient());
		$this->assertEquals('system', $instance->getGroup());
	}
}
