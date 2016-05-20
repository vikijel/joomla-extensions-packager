<?php
/**
 * @author: Viktor Jelínek  (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

use XMLWriter;

class Xml
{
	protected static $generic_properties = [
		'name',
		'version',
		'author',
		'authorEmail',
		'authorUrl',
		'copyright',
		'creationDate',
		'description',
		'license',
		'url',
		'scriptfile',
		'packager',
		'packagerUrl',
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

		$this->writeAttributeIfValueNotEmpty('type', $this->package->getPkgType());
		$this->writeAttributeIfValueNotEmpty('version', $this->package->getPkgVersion());
		$this->writeAttributeIfValueNotEmpty('method', $this->package->getPkgMethod());

		$this->initProperties();
		$this->initFiles();
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

	protected function initFiles()
	{
		$this->writer->startElement('files');

		foreach ($this->package->getExtensions() as $ext)
		{
			$this->writer->startElement('file');

			$this->writeAttributeIfValueNotEmpty('type', $ext->getType());
			$this->writeAttributeIfValueNotEmpty('id', $ext->getName());
			$this->writeAttributeIfValueNotEmpty('group', $ext->getGroup());
			$this->writeAttributeIfValueNotEmpty('client', $ext->getClient());

			$this->writer->text(basename($ext->getFile()));
			$this->writer->endElement();
		}

		$this->writer->endElement();
	}

	protected function initLanguages()
	{
		if (!empty($this->package->getLanguages()))
		{
			$this->writer->startElement('languages');

			foreach ($this->package->getLanguages() as $lang)
			{
				$this->writer->startElement('language');
				$this->writeAttributeIfValueNotEmpty('tag', $lang->getTag());
				$this->writer->text(basename($lang->getFile()));
				$this->writer->endElement();
			}

			$this->writer->endElement();
		}
	}

	protected function initUpdateServers()
	{
		if (!empty($this->package->getPkgUpdateservers()))
		{
			$this->writer->startElement('updateservers');

			foreach ($this->package->getPkgUpdateservers() as $server)
			{
				$this->writer->startElement('server');

				$this->writeAttributeIfValueNotEmpty('type', $server->getType());
				$this->writeAttributeIfValueNotEmpty('priority', $server->getPriority());
				$this->writeAttributeIfValueNotEmpty('name', $server->getName());

				$this->writer->text($server->getUrl());
				$this->writer->endElement();
			}

			$this->writer->endElement();
		}
	}

	protected function writeElementIfValueNotEmpty($name, $value)
	{
		$value = trim($value);

		if ($value != '')
		{
			$this->writer->writeElement($name, $value);
		}
	}

	protected function writeAttributeIfValueNotEmpty($name, $value)
	{
		$value = trim($value);

		if ($value != '')
		{
			$this->writer->writeAttribute($name, $value);
		}
	}
}
