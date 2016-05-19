<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

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
	 */
	public function setName($name)
	{
		$this->name = Helper::toSystemName($name);

		return $this;
	}

	/**
	 * @return string Path to extension's install package
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param string $file Path to extension's install package
	 *
	 * @return Extension
	 */
	public function setFile($file)
	{
		$this->file = Helper::toFilePath($file);

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
	 */
	public function setClient($client = null)
	{
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
	 */
	public function setGroup($group = null)
	{
		$this->group = $group !== null ? Helper::toSystemName($group) : $group;

		return $this;
	}
}