<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
	static $file = 'some\path/to\\file.ini ';
	static $tag  = ' En - gB ';

	public function testClassInstantiates()
	{
		$instance = new Language(static::$file, static::$tag);

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $instance);
	}

	public function testSetsGetsProperties()
	{
		$instance = new Language(static::$file, static::$tag);

		$this->assertEquals('some' . DIRECTORY_SEPARATOR . 'path' . DIRECTORY_SEPARATOR . 'to' . DIRECTORY_SEPARATOR . 'file.ini',
		                    $instance->getFile());
		$this->assertEquals('en-GB', $instance->getTag());
	}
}
