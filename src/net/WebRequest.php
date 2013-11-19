<?php
/**
 * Makes a request to a Uniform Resource Identifier (URI).
 */
namespace SiteCatalog\net;

abstract class WebRequest extends \SiteCatalog\core\Object {
	/**
	 * The content length of the request data being sent.
	 */
	public $contentLength = 0;
	
	/**
	 * The content type of the request being sent.
	 */
	public $contentType = null;
	
	/**
	 * The collection of header name/value pairs associated with the request.
	 * @var WebHeaderCollection
	 */
	public $headers = null;
	
	/**
	 * The protocol method to use in the request.
	 */
	public $method = 'GET';
	
	/**
	 * The network proxy address[:port] to use to access this Internet resource.
	 */
	public $proxy = null;
	
	/**
	 * The current instance's URI.
	 */
	public $requestUri = null;
	
	/**
	 * The length of time, in seconds, before the request times out.
	 */
	public $timeout = 30;
	
	/**
	 * Initializes a new WebRequest instance for the specified URI.
	 * 
	 * @param string $uri The URI that identifies the Internet resource.
	 */
	public function __construct($uri) {
		$this->requestUri = $uri;
		$this->headers = new WebHeaderCollection();
	}
	
}
