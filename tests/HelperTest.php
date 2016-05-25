<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class HelperTest extends \PHPUnit_Framework_TestCase
{
	public function testToSystemName()
	{
		self::assertEquals('abc_def_123', Helper::toSystemName(' AbC  deF   123  '));
	}

	public function testToFilePath()
	{
		self::assertEquals(
			DIRECTORY_SEPARATOR . 'AbC' . DIRECTORY_SEPARATOR . 'def' . DIRECTORY_SEPARATOR . 'file.ext',
			Helper::toFilePath('/AbC\\def/file.ext')
		);
	}
}
