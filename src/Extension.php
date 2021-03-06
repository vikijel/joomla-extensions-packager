<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

/**
 * Class Extension
 *
 * @package VikiJel\JoomlaExtensionsPackager
 */
class Extension
{
	/**
	 * @var string System name of extension
	 */
	protected $name;

	/**
	 * @var string Path to extension's install package
	 */
	protected $file;

	/**
	 * @var string Type of extension (component/module/plugin/language/file/library/template)
	 */
	protected $type;

	/**
	 * @var string Client (site/admin) - only some extension types such as modules, templates and language packs
	 */
	protected $client;

	/**
	 * @var string Plugin group (system/content/search/authentication/...) - plugins only
	 */
	protected $group;

	/**
	 * Extension constructor.
	 *
	 * @param string $name   System name of extension
	 * @param string $file   Path to extension's install package
	 * @param string $type   Type of extension (component/module/plugin/language/file/library/template)
	 * @param string $client Client (site/admin) - only some extension types such as modules, templates and language packs
	 * @param string $group  Plugin group (system/content/search/authentication/...) - plugins only
	 *
	 * @throws \Exception
	 */
	public function __construct($name, $file, $type = 'component', $client = null, $group = null)
	{
		$this->setName($name);
		$this->setFile($file);
		$this->setType($type);
		$this->setClient($client);
		$this->setGroup($group);
	}

	/**
	 * @see Extension::__construct()
	 *
	 * @param string $name
	 * @param string $file
	 * @param string $type
	 * @param null   $client
	 * @param null   $group
	 *
	 * @return Extension
	 * @throws \Exception
	 */
	public static function create($name, $file, $type = 'component', $client = null, $group = null)
	{
		return new static($name, $file, $type, $client, $group);
	}

	/**
	 * @return string System name of extension
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name System name of extension
	 *
	 * @return Extension
	 * @throws \InvalidArgumentException
	 */
	public function setName($name)
	{
		$name = trim($name);

		if ($name == '')
		{
			throw new \InvalidArgumentException('Extension Name cannot be empty!');
		}

		$this->name = Helper::toSystemName($name);

		return $this;
	}

	/**
	 * @return File Extension's install package file
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param string $path Path to extension's install package file
	 * @param string $name Override file name
	 *
	 * @return Extension
	 * @throws \Exception
	 */
	public function setFile($path, $name = null)
	{
		$this->file = File::createFromPath($path, $name);

		return $this;
	}

	/**
	 * @param File $file File Extension's install package file
	 *
	 * @return Extension
	 */
	public function setFileInstance(File $file)
	{
		$this->file = $file;

		return $this;
	}

	/**
	 * @return string Type of extension (component/module/plugin/language/file/library/template)
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type Type of extension (component/module/plugin/language/file/library/template)
	 *
	 * @return Extension
	 */
	public function setType($type = 'component')
	{
		$this->type = Helper::toSystemName($type);

		return $this;
	}

	/**
	 * @return string Client (site/admin) - only some extension types such as modules, templates and language packs
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * @param string $client Client (site/admin) - only some extension types such as modules, templates and language packs
	 *
	 * @return Extension
	 * @throws \InvalidArgumentException
	 */
	public function setClient($client = null)
	{
		if ($this->getType() == 'module' and trim($client) == '')
		{
			throw new \InvalidArgumentException('Extension Type ' . $this->getType() . ' requires non-empty Client');
		}

		$this->client = $client !== null ? Helper::toSystemName($client) : $client;

		return $this;
	}

	/**
	 * @return string Plugin group (system/content/search/authentication/...) - plugins only
	 */
	public function getGroup()
	{
		return $this->group;
	}

	/**
	 * @param string $group Plugin group (system/content/search/authentication/...) - plugins only
	 *
	 * @return Extension
	 * @throws \InvalidArgumentException
	 */
	public function setGroup($group = null)
	{
		if ($this->getType() == 'plugin' and trim($group) == '')
		{
			throw new \InvalidArgumentException('Extension Type ' . $this->getType() . ' requires non-empty Group');
		}

		$this->group = $group !== null ? Helper::toSystemName($group) : $group;

		return $this;
	}
}