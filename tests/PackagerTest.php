<?php

namespace VikiJel\JoomlaExtensionsPackager;

class PackagerTest extends \PHPUnit_Framework_TestCase
{
	static $name = 'Something All-In-One';

	protected $path;

	public function tearDown()
	{
		if (file_exists($this->path))
		{
			//unlink($this->path);
		}
	}

	public function testClassInstantiates()
	{
		$instance = new Packager();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Packager', $instance);
	}

	public function testBuildsPackageComplete()
	{
		$this->path = Packager::pack(
			Package::create(static::$name)
			       ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
			       ->setUrl('http://url.com')
			       ->setPkgName('custom_packagename')
			       ->setCopyright('Custom copyright author=%2$s - year=%1$s')
			       ->setDescription('description')
			       ->setLicense('GPL')
			       ->setCreationDate(date('Y-m-d'))
			       ->setPkgMethod('install')
			       ->setPkgPrefix('package_')
			       ->setPkgType('paaackaaaz')
			       ->setPkgVersion('3.2')
			       ->setScriptfile('/path\\to/script.php')
			       ->setVersion('1.2.3')
			       ->setUrl('https:://url.cz')
			       ->addExtension('com_test', 'path/to/com_test.zip')
			       ->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site')
			       ->addExtensionInstance(
				       Extension::create('plg_system_test', 'path/to/plg_system_test.zip', 'plugin')->setGroup('system')
			       )
			       ->addExtension('tpl_test', 'path/to/tpl_test.zip', 'template', 'admin')
			       ->addExtension('lib_test', 'path/to/lib_test.zip', 'library')
			       ->addExtension('lng_test', 'path/to/lng_test.zip', 'language', 'site')
			       ->addExtensionInstance(
				       Extension::create('file_test', 'path/to/file_test.zip', 'file')
			       )
		);

		$this->assertFileExists($this->path);

	}

	public function testBuildsPackageBasic()
	{
		$this->path = Packager::pack(
			Package::create(static::$name)
			       ->addExtension('com_test', 'path/to/com_test.zip')
			       ->addExtension('mod_test', 'path/to/mod_test.zip', 'module', 'site')
			       ->addExtensionInstance(
				       Extension::create('plg_system_test', 'path/to/plg_system_test.zip', 'plugin')->setGroup('system')
			       )
			       ->addExtension('tpl_test', 'path/to/tpl_test.zip', 'template', 'admin')
			       ->addExtension('lib_test', 'path/to/lib_test.zip', 'library')
			       ->addExtension('lng_test', 'path/to/lng_test.zip', 'language', 'site')
			       ->addExtensionInstance(
				       Extension::create('file_test', 'path/to/file_test.zip', 'file')
			       )
		);

		$this->assertFileExists($this->path);
	}

	public function testRunsDry()
	{
		$this->path = Packager::pack(new Package(static::$name), null, null, true);
	}
}
