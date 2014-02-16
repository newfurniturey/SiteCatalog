<?php
/**
 * An automated and interactive tool for cataloging web applications.
 */
namespace net\sitecatalog;
use net\HttpWebRequest;
use net\InternetDomainName;
use util\Html;
use util\String;

class SiteCatalog extends \core\Object {
	/**
	 * Holds a two-level tree that associates a single URL with all URLs found within it.
	 */
	public $tree = array();
	
	/**
	 * The base-domain info of the originating URL to scan.
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
	 * Parses the given list of URL parts into a host-to-path tree.
	 * 
	 * @param array $urlParts URL parts to process.
	 */
	private function _addUrlToTree(array $urlParts) {
		// strip any leading 'www.'
		$host = (substr($urlParts['host'], 0, 4) === 'www.') ? substr($urlParts['host'], 4) : $urlParts['host'];
		if (!isset($this->tree[$host])) {
			$this->tree[$host] = array();
		}
		$top = &$this->tree[$host];
		
		// process any "path" in the current URL by building a full folder-structure layout
		if (isset($urlParts['path'])) {
			$parts = explode('/', trim($urlParts['path'], '/'));
			foreach ($parts as $part) {
				if (!isset($top[$part])) {
					$top[$part] = array();
				}
				$top = &$top[$part];
			}
		}
		
		// process any query-string in the current URL and keep a full list of each seen for the current path
		if (isset($urlParts['query'])) {
			if (!isset($top['?'])) {
				$top['?'] = array();
			}
			if (!in_array($urlParts['query'], $top['?'])) {
				$top['?'][] = $urlParts['query'];
			}
		}
	}
	
	/**
	 * Fetches the specified URL.
	 * 
	 * @param string $url      The URL to fetch.
	 * @return HttpWebResponse The loaded response.
	 */
	private function _fetch($url) {
		$request = new HttpWebRequest($url);
		return $request->getResponse();
	}
	
	/**
	 * Processes the given list of URLs to determine their scheme, host and path.
	 * 
	 * @param array $urls URLs to process.
	 */
	private function _filterUrls(array $urls = array()) {
		foreach ($urls as $url) {
			$url = trim($url);
			$firstChar = substr($url, 0, 1);
			
			if (preg_match('|^([a-z]+:)?/([/\\\])|', $url)) {
				// explicitly contains a host
				if ($firstChar === '/') {
					// the url doesn't contain the scheme; add the base domain's scheme as a default
					$url = sprintf('%s:%s', $this->_baseDomain['scheme'], $url);
				}
			} else if ($firstChar === '/') {
				// relative path on the current base domain
				$url = sprintf('%s://%s%s', $this->_baseDomain['scheme'], $this->_baseDomain['host'], $url);
			} else {
				// no leading protocol or forward-slash; could contain a host or a relative path
				$firstSlashPos = strpos($url, '/');
				$potentialHost = ($firstSlashPos !== false) ? substr($url, 0, $firstSlashPos) : $url;
				if (InternetDomainName::isValidDomain($potentialHost)) {
					// host without the scheme present (or a relative-path matching a host... either way)
					$url = sprintf('%s://%s', $this->_baseDomain['scheme'], $url);
				} else {
					// relative path on the current base domain
					$url = sprintf('%s://%s/%s', $this->_baseDomain['scheme'], $this->_baseDomain['host'], $url);
				}
			}
			
			// build our tree
			$urlParts = String::parse_url($url);
			$this->_addUrlToTree($urlParts);
		}
	}
	
	/**
	 * Processes the response text for URLs in HTML, CSS, Javascript, etc.
	 * 
	 * @param string $response The response to scan.
	 * return array            A list of all URLs found.
	 */
	private function _findUrlsInResponse($response) {
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
		
		$this->_baseDomain = $parts;
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
			$this->_filterUrls($urls);
		}
	}
}
