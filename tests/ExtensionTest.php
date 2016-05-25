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

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = new Extension(static::$name, static::$file, static::$type, static::$client, static::$group);

		self::assertEquals('plg_system_test', $instance->getName());
		self::assertEquals('plg_system_test.zip', $instance->getFile()->getName());
		self::assertEquals('plugin', $instance->getType());
		self::assertEquals(null, $instance->getClient());
		self::assertEquals('system', $instance->getGroup());
	}
}
