<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

use XMLWriter;

//todo: refactor to Manifest??
//todo: move generic_properties from Package??
/**
 * Class Xml
 *
 * @package VikiJel\JoomlaExtensionsPackager
 */
class Xml
{
	/**
	 * @var array
	 */
	protected static $generic_properties = [
		'name',
		'packagename',
		'version',
		'author',
		'authorEmail',
		'authorUrl',
		'copyright',
		'creationDate',
		'description',
		'license',
		'url',
		'packager',
		'packagerurl',
	];

	/**
	 * @var Package
	 */
	protected $package;

	/**
	 * @var XMLWriter
	 */
	protected $writer;

	/**
	 * Xml constructor.
	 *
	 * @param Package $package
	 */
	public function __construct(Package $package)
	{
		$this->package = $package;
		$this->writer  = new XMLWriter();

		$this->writer->openMemory();
		$this->writer->setIndent(true);
		$this->writer->setIndentString("\t");
	}

	/**
	 * @param Package $package
	 *
	 * @return Xml
	 */
	public static function create(Package $package)
	{
		return new static($package);
	}

	/**
	 * @return Package
	 */
	public function getPackage()
	{
		return $this->package;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->writer->outputMemory(false);
	}

	/**
	 * @return $this
	 */
	public function init()
	{
		$this->writer->startElement('extension');

		$this->writeAttributeIfValueNotEmpty('type', $this->package->getType());
		$this->writeAttributeIfValueNotEmpty('version', $this->package->getMinJoomlaVersion());
		$this->writeAttributeIfValueNotEmpty('method', $this->package->getMethod());

		$this->initProperties();
		$this->initScriptfile();
		$this->initExtensions();
		$this->initLanguages();
		$this->initUpdateServers();

		$this->writer->endElement();

		return $this;
	}

	protected function initProperties()
	{
		foreach (self::$generic_properties as $property)
		{
			$getter = 'get' . ucfirst($property);

			if (!method_exists($this->package, $getter))
			{
				continue;
			}

			$this->writeElementIfValueNotEmpty($property, $this->package->$getter());
		}
	}

	protected function initExtensions()
	{
		if (!$this->package->hasExtensions())
		{
			return;
		}

		$this->writer->startElement('files');

		foreach ($this->package->getExtensions() as $ext)
		{
			$this->writer->startElement('file');

			$this->writeAttributeIfValueNotEmpty('type', $ext->getType());
			$this->writeAttributeIfValueNotEmpty('id', $ext->getName());
			$this->writeAttributeIfValueNotEmpty('group', $ext->getGroup());
			$this->writeAttributeIfValueNotEmpty('client', $ext->getClient());

			$this->writer->text($ext->getFile()->getName());
			$this->writer->endElement();
		}

		$this->writer->endElement();
	}

	protected function initScriptfile()
	{
		if ($this->package->hasScriptfile())
		{
			$this->writer->writeElement('scriptfile', $this->package->getScriptfile()->getName());
		}
	}

	protected function initLanguages()
	{
		if (!$this->package->hasLanguages())
		{
			return;
		}

		$this->writer->startElement('languages');

		foreach ($this->package->getLanguages() as $lang)
		{
			if (trim($lang->getTag()) == '' or trim($lang->getFile()->getName()) == '')
			{
				continue;
			}

			$this->writer->startElement('language');
			$this->writeAttributeIfValueNotEmpty('tag', $lang->getTag());
			$this->writer->text($lang->getFile()->getName());
			$this->writer->endElement();
		}

		$this->writer->endElement();
	}

	protected function initUpdateServers()
	{
		if (!$this->package->hasUpdateservers())
		{
			return;
		}
		
		$this->writer->startElement('updateservers');

		foreach ($this->package->getUpdateservers() as $server)
		{
			if (trim($server->getUrl()) == '')
			{
				continue;
			}

			$this->writer->startElement('server');

			$this->writeAttributeIfValueNotEmpty('type', $server->getType());
			$this->writeAttributeIfValueNotEmpty('priority', $server->getPriority());
			$this->writeAttributeIfValueNotEmpty('name', $server->getName());

			$this->writer->text($server->getUrl());
			$this->writer->endElement();
		}

		$this->writer->endElement();
	}

	/**
	 * @param $name
	 * @param $value
	 */
	protected function writeElementIfValueNotEmpty($name, $value)
	{
		$value = trim($value);

		if ($value != '')
		{
			$this->writer->writeElement($name, $value);
		}
	}

	/**
	 * @param $name
	 * @param $value
	 */
	protected function writeAttributeIfValueNotEmpty($name, $value)
	{
		$value = trim($value);

		if ($value != '')
		{
			$this->writer->writeAttribute($name, $value);
		}
	}

	/**
	 * @return XMLWriter
	 */
	public function getWriter()
	{
		return $this->writer;
	}
}
