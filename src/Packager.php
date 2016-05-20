<?php
namespace VikiJel\JoomlaExtensionsPackager;

class Packager
{
	public static function pack(Package $package)
	{
		$package->prepare();

		//todo
		throw new \Exception('TODO: '.print_r($package, true));

		return true;
	}
}
