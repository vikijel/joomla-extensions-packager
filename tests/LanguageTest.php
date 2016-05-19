<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class LanguageTest extends \PHPUnit_Framework_TestCase
{
	public function testClassInstantiates()
	{
		$instance = new Language();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\Language', $instance);
	}
}
