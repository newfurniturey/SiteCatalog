<?php
/**
 * Provides an enhanced (and specific) set of HTML processing on top of DOMDocument.
 */
namespace util;

class Html extends \DOMDocument {
	/**
	 * Flag to indicate whether the comments have been embedded as regular nodes.
	 */
	private $_commentsEmbedded = false;
	
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
		
		// make sure to embed all comments in the document before processing
		$this->_embedComments();
		
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
	 * @inheritDoc
	 */
	public function load($filename, $options = 0) {
		$this->_reset();
		return parent::load($filename, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function loadHTML($source, $options = 0) {
		$this->_reset();
		return parent::loadHTML($source, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function loadHTMLFile($filename, $options = 0) {
		$this->_reset();
		return parent::loadHTMLFile($filename, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function loadXML($source, $options = 0) {
		$this->_reset();
		return parent::loadXML($source, $options);
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
	
	/**
	 * Converts any found comment-nodes into regular HTML and appends them to the
	 * main body.
	 */
	private function _embedComments() {
		if ($this->_commentsEmbedded === true) {
			// we've already embedded comments =]
			return;
		}
		
		$comments = $this->query('//comment()');
		foreach ($comments as $comment) {
			$commentHtml = new Html();
			if ($commentHtml->loadHTML($comment->nodeValue)) {
				foreach ($commentHtml->childNodes as $commentNode) {
					if (($newNode = $this->importNode($commentNode, true)) === false) {
						// if we can't import the node, skip it
						continue;
					}
					$this->documentElement->appendChild($newNode);
				}
			}
		}
		$this->saveHTML();
		
		$this->_commentsEmbedded = true;
	}
	
	/**
	 * Reset specific flags that are relevant to a single document.
	 */
	private function _reset() {
		$this->_domXPath = null;
		$this->_commentsEmbedded = false;
	}
}
