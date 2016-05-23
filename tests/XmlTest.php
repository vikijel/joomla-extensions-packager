<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class XmlTest extends \PHPUnit_Framework_TestCase
{
	public static $name = 'Package Test';

	public function testClassInstantiates()
	{
		$package  = new Package(static::$name);
		$instance = new Xml($package);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Xml', $instance);
	}
}
