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
		$package  = new Package(self::$name);
		$instance = new Xml($package);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Xml', $instance);
	}

	public function testInits()
	{
		$package    = Package::create(self::$name)->setAuthor('VikiJel')->addUpdateServer('http://example.com', 'testserver');
		$instance   = Xml::create($package)->init();
		$simple_xml = simplexml_load_string((string) $instance);

		self::assertEquals(self::$name, (string) $simple_xml->name);
		self::assertEquals('1.0.0', (string) $simple_xml->version);
		self::assertEquals(date('Y-m-d'), (string) $simple_xml->creationDate);
		self::assertEquals('http://example.com', (string) $simple_xml->updateservers->server);
		self::assertEquals('testserver', (string) $simple_xml->updateservers->server->attributes()->name);
		self::assertEquals(1, (int) $simple_xml->updateservers->server->attributes()->priority);
		self::assertEquals('extension', (string) $simple_xml->updateservers->server->attributes()->type);
		self::assertContains('VikiJel', (string) $simple_xml->copyright);
		self::assertContains(date('Y'), (string) $simple_xml->copyright);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Xml', $instance);
	}
}
