<?php
/**
 * An automated and interactive tool for cataloging web applications.
 */
namespace net\sitecatalog;
use net\HttpWebRequest;
use util\Html;
use util\String;

class SiteCatalog extends \core\Object {
	/**
	 * Holds a two-level tree that associates a single URL with all URLs found within it.
	 */
	public $tree = array();
	
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
		$queue = array($this->_baseUrl);
		$this->_scan($queue);
	}
	
	/**
	 * Fetches the specified URL.
	 * 
	 * @param string $url The URL to fetch.
	 */
	private function _fetch($url) {
		$request = new HttpWebRequest($url);
		return $request->getResponse();
	}
	
	/**
	 * Processes the response text for URLs in HTML, CSS, Javascript, etc.
	 * 
	 * @param string $response The response to scan.
	 * return array            A list of all URLs found.
	 */
	private function _findUrlsInResponse($response) {
		$urls = array();
		
		// create the document model and fetch the contained URLs
		$document = new Html();
		$document->loadHTML($response);
		$urls = $document->getContainedUrls(true);
		
		// return a unique list of URLs
		return array_unique($urls);
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
	
	/**
	 * Processes the entire queue of URLs to scan until it's empty.
	 * 
	 * @param array $queue A first-in-first-out list of URLs to scan.
	 */
	private function _scan(array $queue = array()) {
		$url = null;
		while (($url = array_shift($queue)) !== null) {
			$response = $this->_fetch($url);
			
			$urls = $this->_findUrlsInResponse($response);
			$this->tree[$url] = $urls;
			// @todo filter URLs to only ones we haven't seen before
		}
	}
}
