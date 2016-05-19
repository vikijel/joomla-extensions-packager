<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
	public function testClassAutoloads()
	{
		$instance = new Extension();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $instance);
	}
}
