<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class XmlTest extends \PHPUnit_Framework_TestCase
{
	public static $name        = 'Package Test';
	public static $archive_src = __DIR__ . '/data/some_file.zip';
	public static $php_src     = __DIR__ . '/data/some_file.php';
	public static $ini_src     = __DIR__ . '/data/some_file.ini';

	public function testClassInstantiates()
	{
		$package  = new Package(self::$name);
		$instance = new Xml($package);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Xml', $instance);
	}

	public function testInits()
	{
		$package    = Package::create(self::$name)
		                     ->setAuthor('VikiJel')
		                     ->addExtension('com_test', self::$archive_src)
		                     ->addLanguage(self::$ini_src)
		                     ->addUpdateServer('http://example.com', 'testserver')
		                     ->setScriptfile(self::$php_src);
		$instance   = Xml::create($package)->init();
		$simple_xml = simplexml_load_string((string) $instance);

		self::assertEquals('extension', (string) $simple_xml->getName());
		self::assertEquals('2.5', (string) $simple_xml->attributes()->version);
		self::assertEquals('upgrade', (string) $simple_xml->attributes()->method);
		self::assertEquals('package', (string) $simple_xml->attributes()->type);
		self::assertEquals('1.0.0', (string) $simple_xml->version);
		self::assertEquals(self::$name, (string) $simple_xml->name);
		self::assertEquals('package_test', (string) $simple_xml->packagename);
		self::assertEquals(date('Y-m-d'), (string) $simple_xml->creationDate);
		self::assertEquals('http://example.com', (string) $simple_xml->updateservers->server);
		self::assertEquals('testserver', (string) $simple_xml->updateservers->server->attributes()->name);
		self::assertEquals(1, (int) $simple_xml->updateservers->server->attributes()->priority);
		self::assertEquals('extension', (string) $simple_xml->updateservers->server->attributes()->type);
		self::assertEquals('com_test', (string) $simple_xml->files->file->attributes()->id);
		self::assertEquals('com_test.zip', (string) $simple_xml->files->file);
		self::assertEquals('en-GB.pkg_package_test.ini', (string) $simple_xml->languages->language);
		self::assertEquals('en-GB', (string) $simple_xml->languages->language->attributes()->tag);
		self::assertEquals('pkg_package_test.php', (string) $simple_xml->scriptfile);
		self::assertContains('VikiJel', (string) $simple_xml->copyright);
		self::assertContains(date('Y'), (string) $simple_xml->copyright);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Xml', $instance);
	}
}
