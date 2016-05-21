<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class FileTest extends \PHPUnit_Framework_TestCase
{
	static $path = __DIR__.'/data/some_file.php';

	public function testClassInstantiates()
	{
		$instance = File::createFromPath(static::$path);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\File', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = File::createFromPath(static::$path);

		$this->assertEquals(Helper::toFilePath(static::$path), $instance->getName());
		$this->assertNotNull($instance->getData());
	}
}
