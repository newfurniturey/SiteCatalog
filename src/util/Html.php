<?php
/**
 * Provides an enhanced (and specific) set of HTML processing on top of DOMDocument.
 */
namespace util;

class Html extends \DOMDocument {
	/**
	 * XPath object for performing xpath queries.
	 * 
	 * @var \DOMXPath 
	 */
	private $_domXPath = null;
	
	/**
	 * Run a few extensions upon initialization.
	 * 
	 * @param string $version  The version number of the document as part of the XML declaration.
	 * @param string $encoding The encoding of the document as part of the XML declaration.
	 */
	public function __construct($version = null, $encoding = null) {
		// disable standard libxml errors
		libxml_use_internal_errors(true);
		parent::__construct($version, $encoding);
		
		// make sure we extend everything fully
		$this->registerNodeClass('\DOMDocument', get_called_class());
	}
	
	/**
	 * Combines the `href` and `src` attributes on tags to build a general list of URLs found
	 * in the given HTML's source.
	 * 
	 * @param boolean $returnArray if true, an array-list of all URLs will be returned; otherwise the DOMNodeList
	 * @return mixed
	 * @todo Implement comment-parsing as well (which may require a custom DOMNodesList =/)
	 */
	public function getContainedUrls($returnArray = false) {
		static $path = '
			//*/@href
			| //*/@src
			| //*/@xmlns
			| //*/object/@codebase
			| //*/object/@data
			| //*/embed/@pluginspage
			| //*/param[@name="src" or @name="url" or @name="filename"]/@value
		';
		
		// get all straightforward matches of the path
		$nodes = $this->query($path);
		
		if (!$returnArray) {
			return $nodes;
		} else {
			$urls = array();
			foreach ($this->query($path) as $node) {
				$urls[] = $node->nodeValue;
			}
			return $urls;
		}
	}
	
	/**
	 * Evaluate an XPath-query and return the results, if any.
	 * 
	 * @param string $query XPath-query to run.
	 * @return \DOMNodeList A list of all found nodes matching the query.
	 */
	public function query($query) {
		if ($this->_domXPath === null) {
			$this->_domXPath = new \DOMXPath($this);
		}
		
		return $this->_domXPath->evaluate($query);
	}
}
