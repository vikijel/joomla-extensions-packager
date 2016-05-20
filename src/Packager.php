<?php
namespace VikiJel\JoomlaExtensionsPackager;

use Exception;
use ZipArchive;

class Packager
{
	protected static $default_target_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'out';

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
		$zip  = new ZipArchive();

		if ((!file_exists($dir) and !mkdir($dir)) or !is_dir($dir))
		{
			throw new Exception("Cannot create/open directory for writing, dir = '$dir'");
		}

		if ($zip->open($path, ZipArchive::CREATE) !== true)
		{
			throw new Exception("Cannot create/open archive for writing, path = '$path'");
		}

		foreach ($package->getPkgFiles() as $package_file)
		{
			if (!$zip->addFromString($package_file->getName(), $package_file->getData()))
			{
				throw new Exception("Cannot add file to archive, name = '{$package_file->getName()}', path = '$path'");
			}
		}

		if (!$zip->close())
		{
			throw new Exception("Cannot close archive, path = '$path'");
		}

		/*
		// DEBUG:
		throw new Exception(
			"DEBUG:\n" .
			"Target path: " . $path . "\n" .
			"Xml: " . var_export($xml, true) .
			"Package: " . print_r($package, true)
		);
		*/

		return $path;
	}
}
