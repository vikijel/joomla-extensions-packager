<?php
/**
 * @author: Viktor Jelínek
 */

namespace VikiJel\JoomlaExtensionsPackager;

use Exception;

class PackageTest extends \PHPUnit_Framework_TestCase
{
	public static $name         = 'Package Test';
	public static $author       = 'Viktor Jelínek';
	public static $author_email = 'vikijel@gmail.com';
	public static $author_url   = 'http://www.vikijel.cz';
	public static $archive_src  = __DIR__ . '/data/some_file.zip';
	public static $php_src      = __DIR__ . '/data/some_file.php';
	public static $ini_src      = __DIR__ . '/data/some_file.ini';

	protected $path;

	public function tearDown()
	{
		if (!empty($this->path) and getenv('PACKAGER_UNLINK') and file_exists($this->path))
		{
			unlink($this->path);
		}
	}

	public function testClassInstantiates()
	{
		$instance = new Package(static::$name);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Package', $instance);
	}

	public function testValidatesName()
	{
		$this->expectException(\InvalidArgumentException::class);
		new Package('');
	}

	public function testValidatesUrlScheme()
	{
		$this->expectException(\InvalidArgumentException::class);
		Package::create(static::$name)->setUrl('example.com');
	}

	public function testValidatesUrlHost()
	{
		$this->expectException(\InvalidArgumentException::class);
		Package::create(static::$name)->setUrl('http://');
	}

	public function testValidatesAuthorUrlScheme()
	{
		$this->expectException(\InvalidArgumentException::class);
		Package::create(static::$name)->setAuthorUrl('example.com');
	}

	public function testValidatesAuthorUrlHost()
	{
		$this->expectException(\InvalidArgumentException::class);
		Package::create(static::$name)->setAuthorUrl('http://');
	}

	public function testValidatesLicense()
	{
		$this->expectException(\InvalidArgumentException::class);
		Package::create(static::$name)->setLicense('MIT');
	}

	public function testGetters()
	{
		$package = new Package(static::$name);
		$package->setAuthor(static::$author);

		self::assertContains($package->getAuthor(), $package->getCopyright());
		self::assertContains(date('Y'), $package->getCopyright());
		self::assertEquals('1.0.0', $package->getVersion());
		self::assertEquals('pkg_' . Helper::toSystemName(static::$name) . '-1.0.0.zip', $package->getPkgFileName());
	}

	public function testSetsGetsAuthor()
	{
		$package = new Package(static::$name);
		$package->setAuthor(static::$author, static::$author_email, static::$author_url);

		self::assertEquals(static::$author, $package->getAuthor());
		self::assertEquals(static::$author_email, $package->getAuthorEmail());
		self::assertEquals(static::$author_url, $package->getAuthorUrl());
	}

	public function testAddsExtensions()
	{
		$package = new Package(static::$name);

		$package->addExtension('mod_test', static::$archive_src, 'module', 'site');

		$extensions = $package->getExtensions();

		self::assertNotEmpty($extensions);

		$count_1 = count($extensions);

		self::assertEquals(1, $count_1);
		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['mod_test']);

		$package->addExtensionInstance(Extension::create('plg_search_stuff', static::$archive_src, 'plugin', null, 'search'));

		$extensions = $package->getExtensions();

		$count_2 = count($extensions);

		self::assertEquals(2, $count_2);
		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['plg_search_stuff']);
	}

	public function testAddsLanguage()
	{
		$package = new Package(static::$name);

		$package->addLanguage(static::$ini_src, 'cs-CZ');

		$languages = $package->getLanguages();

		self::assertNotEmpty($languages);

		$count_1 = count($languages);

		self::assertEquals(1, $count_1);
		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $languages[0]);

		$package->addLanguageInstance(Language::create(static::$ini_src, 'sk-SK'));

		$languages = $package->getLanguages();

		$count_2 = count($languages);

		self::assertEquals(2, $count_2);
		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $languages[1]);
	}

	public function testPacks()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->addLanguage(static::$ini_src, 'cs-CZ')
		                     ->addExtension('mod_test', static::$archive_src, 'module', 'site')
		                     ->addExtensionInstance(Extension::create('plg_search_stuff', static::$archive_src, 'plugin', null, 'search'))
		                     ->pack();

		self::assertFileExists($this->path);
	}

	public function testBuildsPackageComplete()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
		                     ->setUrl('http://example.com')
		                     ->setPackagename($this->getUniqueName(' custom2 packagename2 '))
		                     ->setCopyright('Custom copyright author={author} - year={year}')
		                     ->setDescription('description')
		                     ->setLicense('GPL')
		                     ->setCreationDate(date('Y-m-d'))
		                     ->setMethod('install')
		                     ->setPrefix('package_')
		                     ->setType('paaackaaaz')
		                     ->setMinJoomlaVersion('3.2')
		                     ->setScriptfile(static::$php_src)
		                     ->setVersion('1.2.3')
		                     ->setUrl('https://example.com')
		                     ->addExtension('com_easyredminehelper', static::$archive_src)
		                     ->addExtension('mod_easyredmine_demo', static::$archive_src, 'module', 'site')
		                     ->addExtensionInstance(
			                     Extension::create('plg_system_nonseftosef', static::$archive_src)
			                              ->setGroup('system')
			                              ->setType('plugin')
		                     )
		                     ->addExtension('tpl_esw_easypeasy', static::$archive_src, 'template', 'site')
		                     ->addExtension('lib_test', static::$archive_src, 'library')
		                     ->addExtension('lng_test', static::$archive_src, 'language', 'site')
		                     ->addExtensionInstance(
			                     Extension::create('file_pricingpage', static::$archive_src, 'file')
		                     )
		                     ->addLanguage(static::$ini_src, 'cs-CZ')
		                     ->addLanguage(static::$ini_src, 'en-GB')
		                     ->addUpdateServer('http://updates1.example.com', 'My update server 1')
		                     ->addUpdateServer('http://updates2.example.com', 'My update server 2', 'collection', 2)
		                     ->pack();

		self::assertFileExists($this->path);

	}

	public function testBuildsPackageStandard()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
		                     ->setVersion('1.2.3')
		                     ->setDescription('This is something...')
		                     ->setLicense('GPL')
		                     ->setCreationDate(date('Y-m-d'))
		                     ->setMinJoomlaVersion('3.2')
		                     ->setUrl('http://example.com')
		                     ->setScriptfile(static::$php_src)
		                     ->addExtension('com_test', static::$archive_src)
		                     ->addExtension('mod_test', static::$archive_src, 'module', 'site')
		                     ->addExtensionInstance(
			                     Extension::create('plg_system_test', static::$archive_src, 'plugin')
			                              ->setGroup('system')
		                     )
		                     ->addExtension('tpl_test', static::$archive_src, 'template', 'admin')
		                     ->addExtension('lib_test', static::$archive_src, 'library')
		                     ->addExtension('lng_test', static::$archive_src, 'language', 'site')
		                     ->addExtensionInstance(
			                     Extension::create('file_test', static::$archive_src, 'file')
		                     )->pack();

		self::assertFileExists($this->path);

	}

	public function testBuildsPackageBasic()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->addExtension('com_test', static::$archive_src)
		                     ->addExtension('mod_test', static::$archive_src, 'module', 'site')
		                     ->addExtensionInstance(
			                     Extension::create('plg_system_test', static::$archive_src, 'plugin')
			                              ->setGroup('system')
		                     )
		                     ->addExtension('tpl_test', static::$archive_src, 'template', 'admin')
		                     ->addExtension('lib_test', static::$archive_src, 'library')
		                     ->addExtension('lng_test', static::$archive_src, 'language', 'site')
		                     ->addExtensionInstance(
			                     Extension::create('file_test', static::$archive_src, 'file')
		                     )->pack();

		self::assertFileExists($this->path);
	}

	public function testRunsDry()
	{
		$this->expectException(Exception::class);

		$this->path = Package::create($this->getUniqueName())->pack(null, null, true);

		self::assertNull($this->path);
	}

	protected function getUniqueName($prefix = null)
	{
		return ($prefix !== null ? $prefix : static::$name) . ' ' . uniqid();
	}
}
