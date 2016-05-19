<?php

namespace VikiJel\JoomlaExtensionsPackager;

class PackagerTest extends \PHPUnit_Framework_TestCase
{
	static $name = 'Package Test';

	public function testClassInstantiates()
	{
		$instance = new Packager(new Package(static::$name));

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Packager', $instance);
	}

	public function testBuildsPackage()
	{
		Packager::create()->pack(
			Package::create(static::$name)
			       ->setAuthor('VikiJel', 'vikijel@gmail.com', 'http://vikijel.cz')
			       ->setUrl('http://url.com')
			       ->setPkgName('custom_packagename')
			       ->setCopyright('Custom copyright author=%2$s - year=%1$s')
			       ->setDescription('description')
			       ->setLicense('GPL')
			       ->setPackagerurl('PACKAGERURL')
			       ->setCreationDate(date('Y-m-d'))
			       ->setPkgMethod('install')
			       ->setPkgPrefix('package_')
			       ->setPkgType('paaackaaaz')
			       ->setPkgVersion('3.2')
			       ->setScriptfile('/path\\to/script.php')
			       ->setVersion('1.2.3')
			       ->setUrl('https:://url.cz')
			       ->prepare()
		);
	}
}
