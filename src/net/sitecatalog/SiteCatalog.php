<?php
/**
 * An automated and interactive tool for cataloging web applications.
 */
namespace net\sitecatalog;
use util\String;

class SiteCatalog extends \core\Object {
	/**
	 * The base-domain of the originating URL to scan.
	 */
	private $_baseDomain = null;
	
	/**
	 * The originating URL to scan.
	 */
	private $_baseUrl = null;
	
	/**
	 * The options the current cataloging operation will adhere to.
	 */
	private $_options = array();
	
	/**
	 * Initializes an instance of the SiteCatalog object.
	 * 
	 * @param string $url    The URL to initiate the site-catalog from.
	 * @param array $options List of scanning options to use.
	 * @throws \core\exceptions\ArgumentNullException
	 */
	public function __construct($url, array $options = array()) {
		if (empty($url)) {
			throw new \core\exceptions\ArgumentNullException('$url');
		}
		
		$this->_options = ($options !== null) ? $options : array();
		$this->_processBaseUrl($url);
	}
	
	/**
	 * Initiates a site-catalog scan originating with the current base URL.
	 */
	public function scan() {
		$response = $this->_fetch($this->_baseUrl);
	}
	
	/**
	 * Fetches the specified URL.
	 * 
	 * @param string $url The URL to fetch.
	 */
	public function _fetch($url) {
		$request = new HttpWebRequest($url);
		return $request->getResponse();
	}
	
	/**
	 * Processes the URL to determine the base domain for the current catalog.
	 * 
	 * @param string $url The URL to process.
	 * @throws \InvalidArgumentException
	 */
	private function _processBaseUrl($url) {
		$parts = String::parse_url($url);
		
		if (!is_array($parts) || empty($parts['host'])) {
			throw new \InvalidArgumentException('$url');
		}
		
		$this->_baseDomain = $parts['host'];
		$this->_baseUrl = $url;
	}
}
