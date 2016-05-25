<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class UpdateServerTest extends \PHPUnit_Framework_TestCase
{
	public static $url      = 'http://updates.vikijel.cz/test.xml';
	public static $name     = 'VikiJel';
	public static $type     = 'vikijel';
	public static $priority = 2;

	public function testClassInstantiates()
	{
		$instance = new UpdateServer(self::$url, self::$name, self::$type, self::$priority);

		self::assertInstanceOf('\\VikiJel\\JoomlaExtensionsPackager\\UpdateServer', $instance);
	}
}
