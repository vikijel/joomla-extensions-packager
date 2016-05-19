<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */
namespace VikiJel\JoomlaExtensionsPackager;

class Helper
{
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
}