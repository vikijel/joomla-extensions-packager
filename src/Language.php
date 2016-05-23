<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

use InvalidArgumentException;

/**
 * Class Language
 *
 * @package VikiJel\JoomlaExtensionsPackager
 */
class Language
{
	/**
	 * @var File Language file for package
	 */
	protected $file;

	/**
	 * @var string Language tag like 'en-GB'
	 */
	protected $tag;

	/**
	 * Language constructor.
	 *
	 * @param string $file Path to language file for package (*.ini)
	 * @param string $tag  Language tag like 'en-GB'
	 *
	 * @throws \Exception
	 * @throws \InvalidArgumentException
	 */
	public function __construct($file, $tag = 'en-GB')
	{
		$this->setFile($file);
		$this->setTag($tag);
	}

	/**
	 * @see Language::__construct()
	 *
	 * @param string $file
	 * @param string $tag
	 *
	 * @return Language
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public static function create($file, $tag = 'en-GB')
	{
		return new static($file, $tag);
	}

	/**
	 * @return File
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param string $path
	 * @param string $name
	 *
	 * @return Language
	 * @throws \Exception
	 */
	public function setFile($path, $name = null)
	{
		$this->file = File::createFromPath($path, $name);

		return $this;
	}

	/**
	 * @param File $file
	 *
	 * @return Language
	 */
	public function setFileInstance(File $file)
	{
		$this->file = $file;

		return $this;
	}

	/**
	 * @return string Language tag like 'en-GB'
	 */
	public function getTag()
	{
		return $this->tag;
	}

	/**
	 * @param string $tag Language tag like 'en-GB'
	 *
	 * @return Language
	 * @throws \InvalidArgumentException
	 */
	public function setTag($tag)
	{
		$arr = explode('-', $tag);

		if (count($arr) != 2)
		{
			throw new InvalidArgumentException("Invalid format of language tag '{$tag}'");
		}

		$tag = strtolower(trim($arr[0])) . '-' . strtoupper(trim($arr[1]));

		if (strlen($tag) != 5)
		{
			throw new InvalidArgumentException("Invalid length of language tag '{$tag}'");
		}

		$this->tag = $tag;

		return $this;
	}
}