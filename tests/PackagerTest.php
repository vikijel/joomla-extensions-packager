<?php

namespace VikiJel\JoomlaExtensionsPackager;

class PackagerTest extends \PHPUnit_Framework_TestCase
{
	public function testClassAutoload()
	{
		$packager = new Packager();

		$this->assertInstanceOf('\VikiJel\JoomlaExtensionsPackager\Packager', $packager);
	}
}
