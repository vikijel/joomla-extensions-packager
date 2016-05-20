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

	public function pack(Package $package)
	{
		$package->prepare();

		//todo
		print_r($package);
		throw new \Exception('TODO');
	}
}
