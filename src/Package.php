<?php
/**
 * @author: Viktor Jelínek (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

use Exception;
use ZipArchive;

/**
 * Class Package
 *
 * @package VikiJel\JoomlaExtensionsPackager
 */
class Package
{
	/**
	 * @var string Default target directory
	 */
	protected static $default_target_dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'out';

	/**
	 * @var string Human name of package
	 */
	protected $name = '';

	/**
	 * @var string Description of package
	 */
	protected $description = 'Custom Joomla Extensions All-In-One package';

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
	 * @var string Licence of package
	 */
	protected $license = 'http://opensource.org/licenses/GPL-3.0 GPL-3.0';

	/**
	 * @var string Copyright of package ({year} will be replaced with actual year, {author} will be replaced with $this->author)
	 */
	protected $copyright = 'Copyright (c){year} {author} - All rights reserved';

	/**
	 * @var string URL of package
	 */
	protected $url = '';

	/**
	 * @var string|bool Date of package creation (True => actual date will be used, False => unset)
	 */
	protected $creationDate = true;

	/**
	 * @var File Install script file
	 */
	protected $scriptfile;

	/**
	 * @var string System name of package
	 */
	protected $pkg_name = '';

	/**
	 * @var string Joomla minimal version
	 */
	protected $pkg_version = '2.5';

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
	 * @var Xml Install xml manifest
	 */
	protected $pkg_xml;

	/**
	 * @var string Packager of package
	 */
	private $packager = 'Joomla! Extensions Packager library made by VikiJel';

	/**
	 * @var string URL of package file
	 */
	private $packagerUrl = 'https://github.com/vikijel/joomla-extensions-packager';

	/**
	 * Package constructor.
	 *
	 * @param string $name Package name like 'Some Custom Package'
	 */
	public function __construct($name)
	{
		$this->setName($name);
	}

	/**
	 * @see Package::__construct()
	 *
	 * @param string $name Package name like 'Some Custom Package'
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
	 * @throws \Exception
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
	 * @throws \Exception
	 * @throws \InvalidArgumentException
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
		$language->getFile()->setName($language->getTag() . '.' . $this->getPkgFileName('ini', false));

		$this->pkg_languages[] = $language;

		return $this;
	}

	/**
	 * @param string $extension
	 * @param bool   $version
	 *
	 * @return string
	 */
	public function getPkgFileName($extension = 'zip', $version = true)
	{
		return $this->getPkgPrefix() . $this->getPkgName() . ($version ? '-' . $this->getVersion() : '') . '.' . $extension;
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
	public function getPkgName()
	{
		$this->setPkgName($this->pkg_name);

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
	public function getVersion()
	{
		$this->setVersion($this->version);

		return $this->version;
	}

	/**
	 * @param string $version
	 *
	 * @return Package
	 */
	public function setVersion($version = '')
	{
		$this->version = ($version != '' and version_compare($version, '0.0.0', '>')) ? $version : '1.0.0';

		return $this;
	}

	/**
	 * @param string $url
	 * @param string $name
	 * @param string $type
	 * @param int    $priority
	 *
	 * @return $this
	 */
	public function addUpdateServer($url, $name = '', $type = 'extension', $priority = 1)
	{
		$this->addUpdateServerInstance(UpdateServer::create($url, $name, $type, $priority));

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
	 * @param File $scriptfile
	 *
	 * @return Package
	 */
	public function setScriptfileInstance(File $scriptfile)
	{
		$this->scriptfile = $scriptfile;

		return $this;
	}

	/**
	 * @param string $dir  Target directory (defaults to ../out)
	 * @param string $file Target filename (defaults to auto-generate from package name)
	 *
	 * @param bool   $dry_run
	 *
	 * @return string Path to created package
	 * @throws Exception
	 */
	public function pack($dir = null, $file = null, $dry_run = false)
	{
		$file = Helper::toFileName(trim($file) == '' ? $this->getPkgFileName() : $file);
		$dir  = Helper::toFilePath(trim($dir) == '' ? self::$default_target_dir : $dir);
		$path = Helper::toFilePath($dir . DIRECTORY_SEPARATOR . $file);

		if ($dry_run)
		{
			throw new Exception(
				"DRY RUN:\n" .
				'$path = ' . $path . "\n" .
				'$this = ' . print_r($this, true)
			);
		}

		$zip = new ZipArchive();

		if (!file_exists($dir) and !@mkdir($dir) && !is_dir($dir))
		{
			throw new Exception("Cannot create/open directory for writing, dir = '$dir'");
		}

		if ($zip->open($path, ZipArchive::CREATE) !== true)
		{
			throw new Exception("Cannot create/open archive for writing, path = '$path'");
		}

		foreach ($this->getFiles() as $package_file)
		{
			if (!$zip->addFromString($package_file->getName(), $package_file->getData()))
			{
				throw new Exception("Cannot add file to archive, name = '{$package_file->getName()}', path = '$path'");
			}
		}

		$zip->setArchiveComment(
			$this->getName() . ($this->getAuthor() != '' ? ' by ' . $this->getAuthor() : '') . "\n" .
			($this->getDescription() != '' ? "\n" . $this->getDescription() . "\n" : '') .
			"\n================================================================\n" .
			'Packed using ' . $this->getPackager() . "\n" .
			$this->getPackagerUrl() . "\n"
		);

		if (!$zip->close())
		{
			throw new Exception("Cannot close archive, path = '$path'");
		}

		return $path;
	}

	/**
	 * @return File[]
	 */
	public function getFiles()
	{
		$this->setFiles();

		return $this->pkg_files;
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
		$this->name = trim($name);

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
	public function getPackager()
	{
		return $this->packager;
	}

	/**
	 * @return string
	 */
	public function getPackagerUrl()
	{
		return $this->packagerUrl;
	}

	/**
	 * @return Package
	 */
	public function setFiles()
	{
		$this->pkg_files   = [];
		$this->pkg_files[] = new File($this->getPkgFileName('xml', false), (string) $this->getPkgXml());

		foreach ($this->getExtensions() as $extension)
		{
			$this->pkg_files[] = $extension->getFile();
		}

		foreach ($this->getLanguages() as $lang)
		{
			$this->pkg_files[] = $lang->getFile();
		}

		if ($this->getScriptfile())
		{
			$this->pkg_files[] = $this->getScriptfile();
		}

		return $this;
	}

	/**
	 * @return Xml
	 */
	public function getPkgXml()
	{
		$this->setPkgXml();

		return $this->pkg_xml;
	}

	/**
	 * @return $this
	 */
	public function setPkgXml()
	{
		$this->pkg_xml = Xml::create($this)->init();

		return $this;
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
	 * @return File
	 */
	public function getScriptfile()
	{
		return $this->scriptfile;
	}

	/**
	 * @param string $path Path to file
	 * @param string $name Override file name
	 *
	 * @return Package
	 * @throws \Exception
	 */
	public function setScriptfile($path, $name = null)
	{
		$this->scriptfile = File::createFromPath($path, $name ?: $this->getPkgFileName('php', false));

		return $this;
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
	 * @param bool $replace_placeholders
	 *
	 * @return string
	 */
	public function getCopyright($replace_placeholders = true)
	{
		if ($replace_placeholders)
		{
			$this->replaceCopyrightPlaceholders();
		}

		return $this->copyright;
	}

	/**
	 * @param string $copyright
	 *
	 * @return Package
	 */
	public function setCopyright($copyright = '')
	{
		$this->copyright = $copyright;

		return $this;
	}

	public function replaceCopyrightPlaceholders()
	{
		if ($this->getAuthor() != '')
		{
			$this->copyright = str_replace(
				['{year}', '{author}', '  '],
				[date('Y', strtotime($this->getCreationDate())), $this->getAuthor(), ' '],
				$this->copyright
			);
		}
	}

	/**
	 * @return string
	 */
	public function getCreationDate()
	{
		$this->setCreationDate($this->creationDate);

		return $this->creationDate;
	}

	/**
	 * @param string|bool $creationDate String to set specific date, True to set today, False|null|'' to unset
	 *
	 * @return Package
	 */
	public function setCreationDate($creationDate = true)
	{
		if (is_string($creationDate) and trim($creationDate) != '')
		{
			$this->creationDate = $creationDate;
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
	 * @return UpdateServer[]
	 */
	public function getUpdateservers()
	{
		return $this->pkg_updateservers;
	}
}