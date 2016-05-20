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

		$this->writer->writeAttribute('type', $this->package->getPkgType());
		$this->writer->writeAttribute('version', $this->package->getPkgVersion());
		$this->writer->writeAttribute('method', $this->package->getPkgMethod());

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

			$value = $this->package->$getter();

			if (trim($value) != '')
			{
				$this->writer->writeElement($property, $value);
			}
		}
	}

	protected function initFiles()
	{
		$this->writer->startElement('files');

		foreach ($this->package->getExtensions() as $ext)
		{
			$this->writer->startElement('file');
			$this->writer->writeAttribute('type', $ext->getType());

			if (!empty($ext->getName()))
			{
				$this->writer->writeAttribute('id', $ext->getName());
			}

			if (!empty($ext->getGroup()))
			{
				$this->writer->writeAttribute('group', $ext->getGroup());
			}

			if (!empty($ext->getClient()))
			{
				$this->writer->writeAttribute('client', $ext->getClient());
			}

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
				$this->writer->writeAttribute('tag', $lang->getTag());
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

				if (!empty($server->getType()))
				{
					$this->writer->writeAttribute('type', $server->getType());
				}

				if (!empty($server->getPriority()))
				{
					$this->writer->writeAttribute('priority', $server->getPriority());
				}

				if (!empty($server->getName()))
				{
					$this->writer->writeAttribute('name', $server->getName());
				}

				$this->writer->text($server->getUrl());
				$this->writer->endElement();
			}

			$this->writer->endElement();
		}
	}
}
