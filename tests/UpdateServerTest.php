<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class UpdateServerTest extends \PHPUnit_Framework_TestCase
{
	public function testClassAutoloads()
	{
		$instance = new UpdateServer();

		$this->assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\UpdateServer', $instance);
	}
}
