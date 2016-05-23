<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */
namespace VikiJel\JoomlaExtensionsPackager;

/**
 * Class Helper
 *
 * @package VikiJel\JoomlaExtensionsPackager
 */
class Helper
{
	/**
	 * @param string $name
	 *
	 * @return string
	 */
	public static function toSystemName($name)
	{
		return implode(
			'_',
			array_filter(
				explode(
					' ',
					preg_replace(
						'/[^a-zA-Z0-9]/',
						' ',
						strtolower($name)
					)
				),
				'strlen'
			)
		);
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public static function toFilePath($path)
	{
		return trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path));
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public static function toFileName($path)
	{
		return basename(self::toFilePath($path));
	}
}