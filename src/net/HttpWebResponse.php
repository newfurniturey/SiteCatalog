<?php
/**
 * Provides a HTTP-specific implementation of the WebResponse class.
 * 
 * @todo: Add cookie management.
 * @todo: Add cache management.
 */
namespace SiteCatalog\net;

class HttpWebResponse extends \SiteCatalog\net\WebResponse {
	/**
	 * The method that is used to encode the body of the response.
	 */
	public $contentEncoding = null;
	
	/**
	 * The last date and time that the contents of the response were modified.
	 */
	public $lastModified = null;
	
	/**
	 * The method that is used to return the response.
	 */
	public $method = null;
	
	/**
	 * The version of the HTTP protocol that is used in the response.
	 */
	public $protocolVersion = null;
	
	/**
	 * The name of the server that sent the response.
	 */
	public $server = null;
	
	/**
	 * The status of the response.
	 */
	public $statusCode = null;
	
	/**
	 * The status description returned with the response.
	 */
	public $statusDescription = null;
	
	/**
	 * @inheritDoc
	 */
	protected function _processHeaders() {
		parent::_processHeaders();
		static $map = array(
			WebHeaders::Server => 'server'
		);
		
		foreach ($map as $header => $property) {
			if (isset($this->headers[$header])) {
				$this->{$property} = $this->headers[$header];
			}
		}
	}
}
