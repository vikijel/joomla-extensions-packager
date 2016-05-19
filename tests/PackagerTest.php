<?php

namespace VikiJel\JoomlaExtensionsPackager;

class PackagerTest extends \PHPUnit_Framework_TestCase
{
	public function testClassInstantiates()
	{
		$instance = new Packager();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Packager', $instance);
	}
}
