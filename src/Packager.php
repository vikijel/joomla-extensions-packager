<?php
namespace VikiJel\JoomlaExtensionsPackager;

use Exception;
use ZipArchive;

class Packager
{
	//TODO: Merge everything into Package??
	protected static $default_target_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'out';

	/**
	 * @param Package $package
	 * @param string  $dir  Target directory (defaults to ../out)
	 * @param string  $file Target filename (defaults to auto-generate from package name)
	 *
	 * @param bool    $dry_run
	 *
	 * @return string Path to created package
	 * @throws Exception
	 */
	public static function pack(Package $package, $dir = null, $file = null, $dry_run = false)
	{
		$package->prepare();

		$file = Helper::toFileName(empty($file) ? $package->getPkgPrefix() . $package->getPkgName() . '-' . $package->getVersion() . '.zip' : $file);
		$dir  = Helper::toFilePath(empty($dir) ? self::$default_target_dir : $dir);
		$path = Helper::toFilePath($dir . DIRECTORY_SEPARATOR . $file);

		if ($dry_run)
		{
			throw new Exception(
				"DRY RUN:\n" .
				'$path = ' . $path . "\n" .
				'$package = ' . print_r($package, true)
			);
		}

		$zip = new ZipArchive();

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

		$zip->setArchiveComment(
			"{$package->getName()}\n" .
			($package->getDescription() != '' ? $package->getDescription() : "Custom Joomla Extensions All-In-One package") . "\n\n" .
			'Packed at ' . date('Y-m-d H:i:s') . "\n" .
			'Packed by ' . $package->getPackager() . "\n" .
			$package->getPackagerUrl() . "\n"
		);

		if (!$zip->close())
		{
			throw new Exception("Cannot close archive, path = '$path'");
		}

		return $path;
	}
}
