<?php
namespace VikiJel\JoomlaExtensionsPackager;

class Packager
{
	public static function pack(Package $package)
	{
		$package->prepare();

		//todo
		print_r($package);
		throw new \Exception('TODO');

		return true;
	}
}
