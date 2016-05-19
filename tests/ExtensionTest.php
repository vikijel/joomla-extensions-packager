<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class ExtensionTest extends \PHPUnit_Framework_TestCase
{
	public function testClassInstantiates()
	{
		$instance = new Extension('com_test', 'C:\\wamp\\www\\SomeFile.zip');

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $instance);
	}
}
