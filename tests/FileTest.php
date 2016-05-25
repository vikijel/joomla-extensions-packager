<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class FileTest extends \PHPUnit_Framework_TestCase
{
	public static $path = __DIR__ . '/data/some_file.php';

	public function testClassInstantiates()
	{
		$instance = File::createFromPath(static::$path);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\File', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = File::createFromPath(static::$path);

		self::assertEquals(Helper::toFileName(static::$path), $instance->getName());
		self::assertEquals(file_get_contents(Helper::toFilePath(static::$path)), $instance->getData());
		self::assertNotNull($instance->getData());
	}
}
