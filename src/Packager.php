<?php
namespace VikiJel\JoomlaExtensionsPackager;

use ZipArchive;

class Packager
{
	protected static $default_target_dir = __DIR__ . '..' . DIRECTORY_SEPARATOR . 'out';

	/**
	 * @param Package $package
	 * @param string  $dir  Location of target file (defaults to ../out)
	 * @param string  $file Filename of target file (defaults to auto-generate from package name)
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function pack(Package $package, $dir = null, $file = null)
	{
		$package->prepare();

		$file = empty($file) ? $package->getPkgPrefix() . $package->getPkgName() . '-' . $package->getVersion() . '.zip' : $file;
		$dir  = empty($dir) ? self::$default_target_dir : $dir;
		$path = $dir . DIRECTORY_SEPARATOR . $file;
		$xml  = $package->getPkgXml(true);
		$zip  = new ZipArchive();

		//todo
		throw new \Exception(
			"TODO:\n" .
			"Target path: " . $path . "\n" .
			"Xml: " . var_export($xml, true) .
			"Package: " . print_r($package, true)
		);

		return true;
	}
}
