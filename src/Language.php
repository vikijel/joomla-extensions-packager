<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class Language
{
	/**
	 * @var string Path to language file for package (*.ini)
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
	 */
	public static function create($file, $tag = 'en-GB')
	{
		return new static($file, $tag);
	}

	/**
	 * @return string Path to language file for package (*.ini)
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param string $file Path to language file for package (*.ini)
	 *
	 * @return Language
	 */
	public function setFile($file)
	{
		$this->file = Helper::toFilePath($file);

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
	 */
	public function setTag($tag)
	{
		$arr = explode('-', $tag);

		if (count($arr) != 2)
		{
			throw new   \InvalidArgumentException("Invalid format of language tag '{$tag}'");
		}

		$tag = strtolower(trim($arr[0])) . '-' . strtoupper(trim($arr[1]));

		if (strlen($tag) != 5)
		{
			throw new   \InvalidArgumentException("Invalid length of language tag '{$tag}'");
		}

		$this->tag = $tag;

		return $this;
	}
}