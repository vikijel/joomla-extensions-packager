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

		$package->addExtensionInstance(Extension::create('plg_search_stuff', '/var/www/something.zip', 'plugin', null, 'search'));

		$extensions = $package->getExtensions();

		$count_2 = count($extensions);

		$this->assertEquals(2, $count_2);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Extension', $extensions['plg_search_stuff']);
	}

	public function testAddsLanguage()
	{
		$package = new Package(static::$name);

		$package->addLanguage('/var/www/something.ini', 'cs-CZ');

		$languages = $package->getLanguages();

		$this->assertNotEmpty($languages);

		$count_1 = count($languages);

		$this->assertEquals(1, $count_1);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $languages[0]);

		$package->addLanguageInstance(Language::create('/var/www/something2.ini', 'sk-SK'));

		$languages = $package->getLanguages();

		$count_2 = count($languages);

		$this->assertEquals(2, $count_2);
		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $languages[1]);
	}

	public function testPacks()
	{
		$this->path = Package::create(self::getUniqueName(static::$name))
		                     ->addLanguage('/var/www/something.ini', 'cs-CZ')
		                     ->addExtension('mod_test', '/var/www/something.zip', 'module', 'site')
		                     ->addExtensionInstance(Extension::create('plg_search_stuff', '/var/www/something.zip', 'plugin', null, 'search'))
		                     ->pack();

		$this->assertFileExists($this->path);
	}

	// public function testBuildsPackageComplete()
	// {
	// 	$this->path = Packager::pack(
	// 		Package::create($this->getUniqueName())
	// 		       ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
	// 		       ->setUrl('http://url.com')
	// 		       ->setPkgName($this->getUniqueName('custom packagename '))
	// 		       ->setCopyright('Custom copyright author=%2$s - year=%1$s')
	// 		       ->setDescription('description')
	// 		       ->setLicense('GPL')
	// 		       ->setCreationDate(date('Y-m-d'))
	// 		       ->setPkgMethod('install')
	// 		       ->setPkgPrefix('package_')
	// 		       ->setPkgType('paaackaaaz')
	// 		       ->setPkgVersion('3.2')
	// 		       ->setScriptfile('/path\\to/script.php')
	// 		       ->setVersion('1.2.3')
	// 		       ->setUrl('https:://url.cz')
	// 		       ->addExtension('com_test', 'path/to/com_test.zip')
	// 		       ->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site')
	// 		       ->addExtensionInstance(
	// 			       Extension::create('plg_system_test', 'path/to/plg_system_test.zip', 'plugin')->setGroup('system')
	// 		       )
	// 		       ->addExtension('tpl_test', 'path/to/tpl_test.zip', 'template', 'admin')
	// 		       ->addExtension('lib_test', 'path/to/lib_test.zip', 'library')
	// 		       ->addExtension('lng_test', 'path/to/lng_test.zip', 'language', 'site')
	// 		       ->addExtensionInstance(
	// 			       Extension::create('file_test', 'path/to/file_test.zip', 'file')
	// 		       )
	// 	);
	//
	// 	$this->assertFileExists($this->path);
	//
	// }
	//
	// public function testBuildsPackageStandard()
	// {
	// 	$this->path = Packager::pack(
	// 		Package::create($this->getUniqueName())
	// 		       ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
	// 		       ->setVersion('1.2.3')
	// 		       ->setDescription('This is something...')
	// 		       ->setLicense('GPL')
	// 		       ->setCreationDate(date('Y-m-d'))
	// 		       ->setPkgVersion('3.2')
	// 		       ->setUrl('http://url.com')
	// 		       ->setScriptfile('/path\\to/script.php')
	// 		       ->addExtension('com_test', 'path/to/com_test.zip')
	// 		       ->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site')
	// 		       ->addExtensionInstance(
	// 			       Extension::create('plg_system_test', 'path/to/plg_system_test.zip', 'plugin')->setGroup('system')
	// 		       )
	// 		       ->addExtension('tpl_test', 'path/to/tpl_test.zip', 'template', 'admin')
	// 		       ->addExtension('lib_test', 'path/to/lib_test.zip', 'library')
	// 		       ->addExtension('lng_test', 'path/to/lng_test.zip', 'language', 'site')
	// 		       ->addExtensionInstance(
	// 			       Extension::create('file_test', 'path/to/file_test.zip', 'file')
	// 		       )
	// 	);
	//
	// 	$this->assertFileExists($this->path);
	//
	// }
	//
	// public function testBuildsPackageBasic()
	// {
	// 	$this->path = Packager::pack(
	// 		Package::create($this->getUniqueName())
	// 		       ->addExtension('com_test', 'path/to/com_test.zip')
	// 		       ->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site')
	// 		       ->addExtensionInstance(
	// 			       Extension::create('plg_system_test', 'path/to/plg_system_test.zip', 'plugin')->setGroup('system')
	// 		       )
	// 		       ->addExtension('tpl_test', 'path/to/tpl_test.zip', 'template', 'admin')
	// 		       ->addExtension('lib_test', 'path/to/lib_test.zip', 'library')
	// 		       ->addExtension('lng_test', 'path/to/lng_test.zip', 'language', 'site')
	// 		       ->addExtensionInstance(
	// 			       Extension::create('file_test', 'path/to/file_test.zip', 'file')
	// 		       )
	// 	);
	//
	// 	$this->assertFileExists($this->path);
	// }
	//
	// public function testRunsDry()
	// {
	// 	$this->expectException(Exception::class);
	//
	// 	$this->path = Packager::pack(new Package($this->getUniqueName()), null, null, true);
	//
	// 	$this->assertNull($this->path);
	// }

	protected function getUniqueName($prefix = null)
	{
		return ($prefix !== null ? $prefix : static::$name) . ' ' . uniqid();
	}
}
