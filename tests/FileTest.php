<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class FileTest extends \PHPUnit_Framework_TestCase
{
	public function testClassInstantiates()
	{
		$instance = new File();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\File', $instance);
	}
}
