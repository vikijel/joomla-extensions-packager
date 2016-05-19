<?php
namespace VikiJel\JoomlaExtensionsPackager;

class Packager
{
	public function __construct()
	{
	}

	public static function create()
	{
		return new static();
	}

	public function build(Package $package)
	{
		//todo
		print_r($package);
	}
}
