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
		$this->name   = Helper::toSystemName($name);
		$this->file   = escapeshellarg(trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $file)));
		$this->type   = Helper::toSystemName($type);
		$this->client = $client !== null ? Helper::toSystemName($client) : $client;
		$this->group  = $group !== null ? Helper::toSystemName($group) : $group;
	}
}