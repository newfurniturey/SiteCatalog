<?php
/**
 * Makes a request to a Uniform Resource Identifier (URI).
 */
namespace SiteCatalog\net;

abstract class WebRequest {
	/**
	 * The current instance's URI.
	 */
	protected $_uri = null;
	
	/**
	 * Initializes a new WebRequest instance for the specified URI.
	 * 
	 * @param string $uri The URI that identifies the Internet resource.
	 */
	public function __construct($uri) {
		$this->_uri = $uri;
	}
}
