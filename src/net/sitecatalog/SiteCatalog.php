<?php
/**
 * An automated and interactive tool for cataloging web applications.
 */
namespace net\sitecatalog;

class SiteCatalog extends \core\Object {
	/**
	 * The domain to catalog.
	 */
	private $_domain = null;
	
	/**
	 * The options the current cataloging operation will adhere to.
	 */
	private $_options = array();
	
	/**
	 * Initializes an instance of the SiteCatalog object.
	 * 
	 * @param string $domain The domain to catalog.
	 * @param array $options List of scanning options to use.
	 * @throws \core\exceptions\ArgumentNullException
	 */
	public function __construct($domain, array $options = array()) {
		if (empty($domain)) {
			throw new \core\exceptions\ArgumentNullException('$domain');
		}
		
		$this->_domain = $domain;
		$this->_options = ($options !== null) ? $options : array();
	}
	
}
