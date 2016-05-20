<?php
/**
 * @author: Viktor Jelínek (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

use XMLWriter;

class Package
{
	/**
	 * @var string Human name of package
	 */
	protected $name = '';

	/**
	 * @var string Description of package
	 */
	protected $description = '';

	/**
	 * @var string Version of package
	 */
	protected $version = '';

	/**
	 * @var string Author of package
	 */
	protected $author = '';

	/**
	 * @var string Email of package author
	 */
	protected $authorEmail = '';

	/**
	 * @var string URL of package author
	 */
	protected $authorUrl = '';

	/**
	 * @var string Packager of package
	 */
	protected $packager = 'vikijel/joomla-extensions-packager';

	/**
	 * @var string Licence of package
	 */
	protected $license = 'http://opensource.org/licenses/GPL-3.0 GPL-3.0';

	/**
	 * @var string Copyright of package (%1$s will be filled with actual year, %2$s will become author)
	 */
	protected $copyright = 'Copyright %1$s %2$s - All rights reserved.';

	/**
	 * @var string URL of package
	 */
	protected $url = '';

	/**
	 * @var string URL of package file
	 */
	protected $packagerurl = '';

	/**
	 * @var string Date of package creation (if empty, actual date will be used)
	 */
	protected $creationDate = '';

	/**
	 * @var string Install script filename
	 */
	protected $scriptfile = '';

	/**
	 * @var string System name of package
	 */
	protected $pkg_name = '';

	/**
	 * @var string Joomla minimal version
	 */
	protected $pkg_version = '3.5';

	/**
	 * @var string Package type
	 */
	protected $pkg_type = 'package';

	/**
	 * @var string Package install method
	 */
	protected $pkg_method = 'upgrade';

	/**
	 * @var string Prefix for archive and xml filenames
	 */
	protected $pkg_prefix = 'pkg_';

	/**
	 * @var array List of extensions contained in package
	 */
	protected $pkg_extensions = [];

	/**
	 * @var array List of languages contained in package
	 */
	protected $pkg_languages = [];

	/**
	 * @var array List of package update servers
	 */
	protected $pkg_updateservers = [];

	/**
	 * @var array List of filepaths to be packed into package
	 */
	protected $pkg_files = [];
	/**
	 * @var XMLWriter Install xml manifest
	 */
	protected $pkg_xml;

	protected static $pkg_xml_properties = [
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
		'packagerurl',
	];

	public function __construct($name)
	{
		$this->setName($name);
	}

	/**
	 * @see Package::__construct()
	 *
	 * @return Package
	 */
	public static function create($name)
	{
		return new static($name);
	}

	/**
	 * @param string $name   System name of extension
	 * @param string $file   Path to extension's install package
	 * @param string $type   Type of extension (component/module/plugin/language/file/library/template)
	 * @param string $client Client (site/admin) - only some extension types such as modules, templates and language packs
	 * @param string $group  Plugin group (system/content/search/authentication/...) - plugins only
	 *
	 * @return Package
	 */
	public function addExtension($name, $file, $type = 'component', $client = null, $group = null)
	{
		return $this->addExtensionInstance(Extension::create($name, $file, $type, $client, $group));
	}

	/**
	 * @param Extension $extension
	 *
	 * @return $this
	 */
	public function addExtensionInstance(Extension $extension)
	{
		$this->pkg_extensions[$extension->getName()] = $extension;

		return $this;
	}

	/**
	 * @param string $file Path to language file for package (*.ini)
	 * @param string $tag  Language tag like 'en-GB'
	 *
	 * @return Package
	 */
	public function addLanguage($file, $tag = 'en-GB')
	{
		return $this->addLanguageInstance(Language::create($file, $tag));
	}

	/**
	 * @param Language $language
	 *
	 * @return $this
	 */
	public function addLanguageInstance(Language $language)
	{
		$this->pkg_languages[] = $language;

		return $this;
	}

	/**
	 * @param UpdateServer $updateserver
	 *
	 * @return $this
	 */
	public function addUpdateServerInstance(UpdateServer $updateserver)
	{
		$this->pkg_updateservers[] = $updateserver;

		return $this;
	}

	/**
	 * Auto-populates some properties before pack
	 */
	public function prepare()
	{
		$this->setCreationDate();
		$this->setCopyright($this->copyright);
		$this->setVersion($this->version);
		$this->setPkgName($this->pkg_name);

		$this->setPkgXml();
		$this->setPkgFiles();

		return $this;
	}

	protected function setPkgXmlFiles()
	{
		$this->pkg_xml->startElement('files');

		foreach ($this->getExtensions() as $ext)
		{
			$this->pkg_xml->startElement('file');
			$this->pkg_xml->writeAttribute('type', $ext->getType());

			if (!empty($ext->getName()))
			{
				$this->pkg_xml->writeAttribute('id', $ext->getName());
			}

			if (!empty($ext->getGroup()))
			{
				$this->pkg_xml->writeAttribute('group', $ext->getGroup());
			}

			if (!empty($ext->getClient()))
			{
				$this->pkg_xml->writeAttribute('client', $ext->getClient());
			}

			$this->pkg_xml->text(basename($ext->getFile()));
			$this->pkg_xml->endElement();
		}

		$this->pkg_xml->endElement();
	}

	protected function setPkgXmlLanguages()
	{
		if (!empty($this->getLanguages()))
		{
			$this->pkg_xml->startElement('languages');

			foreach ($this->getLanguages() as $lang)
			{
				$this->pkg_xml->startElement('language');
				$this->pkg_xml->writeAttribute('tag', $lang->getTag());
				$this->pkg_xml->text(basename($lang->getFile()));
				$this->pkg_xml->endElement();
			}

			$this->pkg_xml->endElement();
		}
	}

	public function setPkgXmlUpdateServers()
	{
		if (!empty($this->getPkgUpdateservers()))
		{
			$this->pkg_xml->startElement('updateservers');

			foreach ($this->getPkgUpdateservers() as $server)
			{
				$this->pkg_xml->startElement('server');

				if (!empty($server->getType()))
				{
					$this->pkg_xml->writeAttribute('type', $server->getType());
				}

				if (!empty($server->getPriority()))
				{
					$this->pkg_xml->writeAttribute('priority', $server->getPriority());
				}

				if (!empty($server->getName()))
				{
					$this->pkg_xml->writeAttribute('name', $server->getName());
				}

				$this->pkg_xml->text($server->getUrl());
				$this->pkg_xml->endElement();
			}

			$this->pkg_xml->endElement();
		}
	}

	protected function setPkgXmlProperties()
	{
		foreach (self::$pkg_xml_properties as $property)
		{
			if (!property_exists($this, $property))
			{
				continue;
			}

			if (trim($this->$property) != '')
			{
				$this->pkg_xml->writeElement($property, $this->$property);
			}
		}
	}

	/**
	 * @return $this
	 */
	public function setPkgXml()
	{
		$this->pkg_xml = new XMLWriter();

		$this->pkg_xml->openMemory();
		$this->pkg_xml->setIndent(true);
		$this->pkg_xml->setIndentString("\t");
		$this->pkg_xml->startElement('extension');

		$this->pkg_xml->writeAttribute('type', $this->pkg_type);
		$this->pkg_xml->writeAttribute('version', $this->pkg_version);
		$this->pkg_xml->writeAttribute('method', $this->pkg_method);

		$this->setPkgXmlProperties();
		$this->setPkgXmlFiles();
		$this->setPkgXmlLanguages();
		$this->setPkgXmlUpdateServers();

		$this->pkg_xml->endElement();

		return $this;
	}

	/**
	 * @return XMLWriter
	 */
	public function getPkgXml($as_string)
	{
		if (!($this->pkg_xml instanceof XMLWriter))
		{
			$this->prepare();
		}

		return $as_string ? $this->pkg_xml->outputMemory(false) : $this->pkg_xml;
	}

	/**
	 * @return Extension[]
	 */
	public function getExtensions()
	{
		return $this->pkg_extensions;
	}

	/**
	 * @return Language[]
	 */
	public function getLanguages()
	{
		return $this->pkg_languages;
	}

	/**
	 * @return string
	 */
	public function getPkgVersion()
	{
		return $this->pkg_version;
	}

	/**
	 * @param string $pkg_version
	 *
	 * @return Package
	 */
	public function setPkgVersion($pkg_version)
	{
		$this->pkg_version = $pkg_version;

		return $this;
	}

	/**
	 * @return Package
	 */
	public function setPkgFiles()
	{
		$this->pkg_files   = [];
		$this->pkg_files[] = new File($this->getPkgPrefix() . $this->getPkgName() . '.xml', $this->getPkgXml(true));

		foreach ($this->getExtensions() as $extension)
		{
			$this->pkg_files[] = File::createFromPath($extension->getFile());
		}

		foreach ($this->getLanguages() as $lang)
		{
			$this->pkg_files[] = File::createFromPath($lang->getFile());
		}

		if ($this->getScriptfile() != '')
		{
			$this->pkg_files[] = File::createFromPath($this->getScriptfile());
		}

		return $this;
	}

	public function getFiles()
	{
		return $this->pkg_files;
	}

	/**
	 * @return string
	 */
	public function getPkgType()
	{
		return $this->pkg_type;
	}

	/**
	 * @param string $pkg_type
	 *
	 * @return Package
	 */
	public function setPkgType($pkg_type)
	{
		$this->pkg_type = $pkg_type;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPkgMethod()
	{
		return $this->pkg_method;
	}

	/**
	 * @param string $pkg_method
	 *
	 * @return Package
	 */
	public function setPkgMethod($pkg_method)
	{
		$this->pkg_method = $pkg_method;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPkgPrefix()
	{
		return $this->pkg_prefix;
	}

	/**
	 * @param string $pkg_prefix
	 *
	 * @return Package
	 */
	public function setPkgPrefix($pkg_prefix)
	{
		$this->pkg_prefix = $pkg_prefix;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return Package
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 *
	 * @return Package
	 */
	public function setVersion($version = '')
	{
		$this->version = version_compare($version, '0.0.0', '>') ? $version : '1.0.0';

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @param string $author
	 * @param string $email
	 * @param string $url
	 *
	 * @return Package
	 */
	public function setAuthor($author, $email = '', $url = '')
	{
		$this->author = $author;

		if ($email != '')
		{
			$this->setAuthorEmail($email);
		}

		if ($url != '')
		{
			$this->setAuthorUrl($url);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorEmail()
	{
		return $this->authorEmail;
	}

	/**
	 * @param string $authorEmail
	 *
	 * @return Package
	 */
	public function setAuthorEmail($authorEmail)
	{
		$this->authorEmail = $authorEmail;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorUrl()
	{
		return $this->authorUrl;
	}

	/**
	 * @param string $authorUrl
	 *
	 * @return Package
	 */
	public function setAuthorUrl($authorUrl)
	{
		$this->authorUrl = $authorUrl;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPackager()
	{
		return $this->packager;
	}

	/**
	 * @return string
	 */
	public function getLicense()
	{
		return $this->license;
	}

	/**
	 * @param string $license
	 *
	 * @return Package
	 */
	public function setLicense($license)
	{
		$this->license = $license;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCopyright()
	{
		return $this->copyright;
	}

	/**
	 * @param string $copyright
	 *
	 * @return Package
	 */
	public function setCopyright($copyright = '')
	{
		if ($copyright != '')
		{
			$this->copyright = $copyright;
		}

		if (strpos($this->copyright, '%') !== false)
		{
			$this->copyright = sprintf($this->copyright, date('Y'), $this->author);
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url
	 *
	 * @return Package
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPackagerurl()
	{
		return $this->packagerurl;
	}

	/**
	 * @param string $packagerurl
	 *
	 * @return Package
	 */
	public function setPackagerurl($packagerurl)
	{
		$this->packagerurl = $packagerurl;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * @param string|bool $creationDate String to set specific date, True to set today, False|null|'' to unset
	 *
	 * @return Package
	 */
	public function setCreationDate($creationDate = true)
	{
		if (is_string($creationDate))
		{
			$this->creationDate = trim($creationDate);
		}
		else
		{
			$this->creationDate = $creationDate ? date('Y-m-d') : null;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getScriptfile()
	{
		return $this->scriptfile;
	}

	/**
	 * @param string $scriptfile
	 *
	 * @return Package
	 */
	public function setScriptfile($scriptfile)
	{
		$this->scriptfile = $scriptfile;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPkgName()
	{
		return $this->pkg_name;
	}

	/**
	 * @param string $pkg_name
	 *
	 * @return Package
	 */
	public function setPkgName($pkg_name = '')
	{
		$this->pkg_name = $this->name;

		if (trim($pkg_name) != '')
		{
			$this->pkg_name = $pkg_name;
		}

		$this->pkg_name = Helper::toSystemName($this->pkg_name);

		return $this;
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
	 * @return Package
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getPkgFiles()
	{
		return $this->pkg_files;
	}

	/**
	 * @return UpdateServer[]
	 */
	public function getPkgUpdateservers()
	{
		return $this->pkg_updateservers;
	}

	/**
	 * @return Language[]
	 */
	public function getPkgLanguages()
	{
		return $this->pkg_languages;
	}
}