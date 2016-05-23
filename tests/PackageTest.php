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

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Package', $instance);
	}

	public function testGetters()
	{
		$package = new Package(static::$name);
		$package->setAuthor(static::$author);

		$this->assertContains($package->getAuthor(), $package->getCopyright());
		$this->assertContains(date('Y'), $package->getCopyright());
		$this->assertEquals('1.0.0', $package->getVersion());
		$this->assertEquals('pkg_' . Helper::toSystemName(static::$name) . '-1.0.0.zip', $package->getPkgFileName());
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

		$package->addExtension('mod_test', static::$archive_src, 'module', 'site');

		$extensions = $package->getExtensions();

		$this->assertNotEmpty($extensions);

		$count_1 = count($extensions);

		$this->assertEquals(1, $count_1);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['mod_test']);

		$package->addExtensionInstance(Extension::create('plg_search_stuff', static::$archive_src, 'plugin', null, 'search'));

		$extensions = $package->getExtensions();

		$count_2 = count($extensions);

		$this->assertEquals(2, $count_2);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['plg_search_stuff']);
	}

	public function testAddsLanguage()
	{
		$package = new Package(static::$name);

		$package->addLanguage(static::$ini_src, 'cs-CZ');

		$languages = $package->getLanguages();

		$this->assertNotEmpty($languages);

		$count_1 = count($languages);

		$this->assertEquals(1, $count_1);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $languages[0]);

		$package->addLanguageInstance(Language::create(static::$ini_src, 'sk-SK'));

		$languages = $package->getLanguages();

		$count_2 = count($languages);

		$this->assertEquals(2, $count_2);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $languages[1]);
	}

	public function testPacks()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->addLanguage(static::$ini_src, 'cs-CZ')
		                     ->addExtension('mod_test', static::$archive_src, 'module', 'site')
		                     ->addExtensionInstance(Extension::create('plg_search_stuff', static::$archive_src, 'plugin', null, 'search'))
		                     ->pack();

		$this->assertFileExists($this->path);
	}

	public function testBuildsPackageComplete()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
		                     ->setUrl('http://url.com')
		                     ->setPkgName($this->getUniqueName(' custom2 packagename2 '))
		                     ->setCopyright('Custom copyright author={author} - year={year}')
		                     ->setDescription('description')
		                     ->setLicense('GPL')
		                     ->setCreationDate(date('Y-m-d'))
		                     ->setPkgMethod('install')
		                     ->setPkgPrefix('package_')
		                     ->setPkgType('paaackaaaz')
		                     ->setPkgVersion('3.2')
		                     ->setScriptfile(static::$php_src)
		                     ->setVersion('1.2.3')
		                     ->setUrl('https:://url.cz')
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

		$this->assertFileExists($this->path);

	}

	public function testBuildsPackageStandard()
	{
		$this->path = Package::create($this->getUniqueName())
		                     ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
		                     ->setVersion('1.2.3')
		                     ->setDescription('This is something...')
		                     ->setLicense('GPL')
		                     ->setCreationDate(date('Y-m-d'))
		                     ->setPkgVersion('3.2')
		                     ->setUrl('http://url.com')
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

		$this->assertFileExists($this->path);

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

		$this->assertFileExists($this->path);
	}

	public function testRunsDry()
	{
		$this->expectException(Exception::class);

		$this->path = Package::create($this->getUniqueName())->pack(null, null, true);

		$this->assertNull($this->path);
	}

	protected function getUniqueName($prefix = null)
	{
		return ($prefix !== null ? $prefix : static::$name) . ' ' . uniqid();
	}
}
