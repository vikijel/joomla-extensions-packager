<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */
namespace VikiJel\JoomlaExtensionsPackager;

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
	 * @param string $file
	 *
	 * @return string
	 */
	public static function toFilePath($file)
	{
		return trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $file));
	}
}