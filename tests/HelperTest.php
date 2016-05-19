<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class HelperTest extends \PHPUnit_Framework_TestCase
{
	public function testToSystemName()
	{
		$this->assertEquals('abc_def_123', Helper::toSystemName(' AbC  deF   123  '));
	}
}
