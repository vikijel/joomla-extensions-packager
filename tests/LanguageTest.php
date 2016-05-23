<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
	public static $file = __DIR__ . '/data/some_file.ini';
	public static $tag  = ' En - gB ';

	public function testClassInstantiates()
	{
		$instance = new Language(static::$file, static::$tag);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = new Language(static::$file, static::$tag);

		$this->assertEquals('some_file.ini', $instance->getFile()->getName());
		$this->assertEquals('en-GB', $instance->getTag());
	}
}
