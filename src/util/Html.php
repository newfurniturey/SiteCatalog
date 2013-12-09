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
