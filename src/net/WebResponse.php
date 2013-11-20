<?php
/**
 * Provides a response from a Uniform Resource Identifier (URI).
 */
namespace SiteCatalog\net;

abstract class WebResponse extends \SiteCatalog\core\Object {
	/**
	 * The content length of the data being received.
	 */
	public $contentLength = 0;
	
	/**
	 * The content type of the data being received.
	 */
	public $contentType = null;
	
	/**
	 * The collection of header name/value pairs associated with the request.
	 * @var WebHeaderCollection
	 */
	public $headers = null;
	
	/**
	 * The URI of the Internet resource that actually responded to the request.
	 */
	public $responseUri = null;
	
	/**
	 * Initializes a new WebResponse instance.
	 */
	public function __construct() {
		$this->headers = new WebHeaderCollection();
	}
	
}
