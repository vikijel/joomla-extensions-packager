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

	public function testClassInstantiates()
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
	}

	public function testSetsGetsAuthor()
	{
		$package = new Package(static::$name);
		$package->setAuthor(static::$author, static::$author_email, static::$author_url);

		$this->assertEquals(static::$author, $package->getAuthor());
		$this->assertEquals(static::$author_email, $package->getAuthorEmail());
		$this->assertEquals(static::$author_url, $package->getAuthorUrl());
	}

	public function testAddsExtensions()
	{
		$package = new Package(static::$name);

		$package->addExtension('mod_test', '/var/www/something.zip', 'module', 'site');

		$extensions = $package->getExtensions();

		$this->assertNotEmpty($extensions);

		$count_1 = count($extensions);

		$this->assertEquals(1, $count_1);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['mod_test']);

		$package->addExtension('plg_search_stuff', '/var/www/something.zip', 'plugin', null, 'search');

		$extensions = $package->getExtensions();

		$count_2 = count($extensions);

		$this->assertEquals(2, $count_2);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['plg_search_stuff']);
	}
}
