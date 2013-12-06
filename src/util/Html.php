<?php
/**
 * Provides an enhanced (and specific) set of HTML processing on top of DOMDocument.
 */
namespace util;

class Html extends \DOMDocument {
	
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
	
}
