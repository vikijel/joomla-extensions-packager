<?php
/**
 * @author: Viktor Jelínek
 */

namespace VikiJel\JoomlaExtensionsPackager;

class PackageTest extends \PHPUnit_Framework_TestCase
{
	static $name         = 'Package Test';
	static $author       = 'Viktor Jelínek';
	static $author_email = 'vikijel@gmail.com';
	static $author_url   = 'http://www.vikijel.cz';

	public function testClassAutoloads()
	{
		$instance = new Package(static::$name);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Package', $instance);
	}

	public function testPrepares()
	{
		$package = new Package(static::$name);
		$package->setAuthor(static::$author);

		$package->prepare();

		$this->assertContains($package->getAuthor(), $package->getCopyright());
		$this->assertContains(date('Y'), $package->getCopyright());
		$this->assertEquals('1.0.0', $package->getVersion());

		print_r($package);
	}

	public function testSetsAuthor()
	{
		$package = new Package(static::$name);
		$package->setAuthor(static::$author, static::$author_email, static::$author_url);

		$this->assertEquals(static::$author, $package->getAuthor());
		$this->assertEquals(static::$author_email, $package->getAuthorEmail());
		$this->assertEquals(static::$author_url, $package->getAuthorUrl());
	}
}
