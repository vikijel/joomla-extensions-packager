<?php
/**
 * @author: Viktor Jelínek (VikiJel)
 */

namespace VikiJel\JoomlaExtensionsPackager;

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
	 * @var string Install xml manifest content
	 */
	protected $pkg_xml = '';

	public function __construct($name)
	{
		$this->setName($name);
	}

	public function prepare()
	{
		$this->setCopyright($this->copyright);
		$this->setVersion($this->version);
		$this->setPkgName($this->pkg_name);
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
	 * @param string $creationDate
	 *
	 * @return Package
	 */
	public function setCreationDate($creationDate)
	{
		$this->creationDate = $creationDate;

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
}