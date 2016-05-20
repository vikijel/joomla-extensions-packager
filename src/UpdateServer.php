<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

class UpdateServer
{
	/**
	 * @var string URL of update server's xml list of updates
	 */
	protected $url;

	/**
	 * @var string Name of update server
	 */
	protected $name;

	/**
	 * @var string Type of update server
	 */
	protected $type;

	/**
	 * @var int Priority of update server
	 */
	protected $priority;

	public function __construct($url, $name = '', $type = '', $priority = 1)
	{
		$this->setUrl($url);
		$this->setName($name);
		$this->setType($type);
		$this->setPriority($priority);
	}

	/**
	 * @see UpdateServer::__construct()
	 *
	 * @param string $url
	 * @param string $name
	 * @param string $type
	 * @param int    $priority
	 *
	 * @return UpdateServer
	 */
	public static function create($url, $name = '', $type = '', $priority = 1)
	{
		return new static($url, $name, $type, $priority);
	}

	/**
	 * @return string URL of update server's xml list of updates
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url URL of update server's xml list of updates
	 *
	 * @return UpdateServer
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string Name of update server
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name Name of update server
	 *
	 * @return UpdateServer
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string Type of update server
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type Type of update server
	 *
	 * @return UpdateServer
	 */
	public function setType($type)
	{
		$this->type = Helper::toSystemName($type);

		return $this;
	}

	/**
	 * @return int Priority of update server
	 */
	public function getPriority()
	{
		return $this->priority;
	}

	/**
	 * @param int $priority Priority of update server
	 *
	 * @return UpdateServer
	 */
	public function setPriority($priority)
	{
		$this->priority = (int) $priority;

		return $this;
	}
}