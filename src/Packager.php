<?php
namespace VikiJel\JoomlaExtensionsPackager;

class Packager
{
	protected static $default_target_dir = __DIR__ . '..' . DIRECTORY_SEPARATOR . 'out';

	public static function pack(Package $package, $target_dir = null)
	{
		$package->prepare();

		$target_dir   = empty($target_dir) ? self::$default_target_dir : $target_dir;
		$manifest_xml = $package->getPkgXml(true);

		//todo
		throw new \Exception(
			"TODO:\n" .
			"target_dir: " . $target_dir . "\n" .
			"manifest_xml: " . print_r($manifest_xml, true).
			"package: " . print_r($package, true)
		);

		return true;
	}
}
