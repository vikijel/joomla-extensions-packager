<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
	public static $name   = 'Plg system test';
	public static $file   = __DIR__ . '/data/some_file.zip';
	public static $type   = 'Plugin';
	public static $client = null;
	public static $group  = 'SYSteM';

	public function testClassInstantiates()
	{
		$instance = new Extension(static::$name, static::$file, static::$type, static::$client, static::$group);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = new Extension(static::$name, static::$file, static::$type, static::$client, static::$group);

		$this->assertEquals('plg_system_test', $instance->getName());
		$this->assertEquals('plg_system_test.zip', $instance->getFile()->getName());
		$this->assertEquals('plugin', $instance->getType());
		$this->assertEquals(null, $instance->getClient());
		$this->assertEquals('system', $instance->getGroup());
	}
}
