<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class File
{
	/**
	 * @var string File name
	 */
	protected $name;
	/**
	 * @var string File contents
	 */
	protected $data;

	/**
	 * File constructor.
	 *
	 * @param string $name File name
	 * @param string $data File contents
	 */
	public function __construct($name, $data = '')
	{
		$this->setName($name);
		$this->setData($data);
	}

	/**
	 * @see File::__construct()
	 *
	 * @param string $name
	 * @param string $data
	 *
	 * @return File
	 */
	public static function create($name, $data = '')
	{
		return new static($name, $data);
	}

	/**
	 * @param string $path Path to file
	 * @param string $name Override file name
	 *
	 * @return File
	 */
	public static function createFromPath($path, $name = null)
	{
		return new static(($name != null ? $name : Helper::toFileName($path)), @file_get_contents(Helper::toFilePath($path)));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return File
	 */
	public function setName($name = null)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param string $data
	 *
	 * @return File
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}
}