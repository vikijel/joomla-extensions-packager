<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

use Exception;

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
	public function __construct($name, $data = null)
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
	public static function create($name, $data = null)
	{
		return new static($name, $data);
	}

	/**
	 * @param string $path Path to file
	 * @param string $name Override file name
	 *
	 * @return File
	 * @throws Exception
	 */
	public static function createFromPath($path, $name = null)
	{
		$path = Helper::toFilePath($path);

		if (!file_exists($path))
		{
			throw new Exception("File '$path' does not exist");
		}

		if (($data = file_get_contents($path)) === false)
		{
			throw new Exception("Cannot get contents of file '$path'");
		}

		return new static($name != null ? $name : $path, $data);
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
		$this->name = Helper::toFileName($name);

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