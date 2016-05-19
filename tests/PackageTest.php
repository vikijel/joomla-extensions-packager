<?php
/**
 * @author: Viktor Jelínek
 */

namespace VikiJel\JoomlaExtensionsPackager;

class PackageTest extends \PHPUnit_Framework_TestCase
{
	public function testClassAutoloads()
	{
		$package = new Package('Package Test');

		$this->assertInstanceOf('\VikiJel\JoomlaExtensionsPackager\Package', $package);
	}

	public function testPackagePrepares()
	{
		$package = new Package('Package Test');
		$package->setAuthor('VikiJel');

		$package->prepare();

		$this->assertContains($package->getAuthor(), $package->getCopyright());
		$this->assertContains(date('Y'), $package->getCopyright());
		$this->assertEquals('1.0.0', $package->getVersion());

		print_r($package);
	}
}
