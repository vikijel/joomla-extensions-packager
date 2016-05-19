<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class XmlTest extends \PHPUnit_Framework_TestCase
{
	public function testClassAutoloads()
	{
		$instance = new Xml();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Xml', $instance);
	}
}
