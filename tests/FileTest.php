<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class FileTest extends \PHPUnit_Framework_TestCase
{
	static $path = 'C:\\wamp\\www\\test.txt';

	public function testClassInstantiates()
	{
		$instance = File::createFromPath(static::$path);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\File', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = File::createFromPath(static::$path);

		$this->assertEquals('test.txt', $instance->getName());
		$this->assertNotNull($instance->getData());
	}
}
