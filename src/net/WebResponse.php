<?php
/**
 * Provides a response from a Uniform Resource Identifier (URI).
 */
namespace SiteCatalog\net;
use SiteCatalog\net\WebHeaderCollection;

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
	 * 
	 * @param \SiteCatalog\net\WebHeadercollection $headers The headers to use for this response; if defined, all convenience properties
	 *                                                      will automatically be populated.
	 */
	public function __construct(WebHeaderCollection $headers = null) {
		if ($headers !== null) {
			if (!($headers instanceof WebHeaderCollection)) {
				throw new \InvalidArgumentException('$headers');
			}
			$this->headers = $headers;
			$this->_processHeaders();
		} else {
			$this->headers = new WebHeaderCollection();
		}
	}
	
	/**
	 * Populates all convenience-properties with specific header data.
	 */
	protected function _processHeaders() {
		static $map = array(
			WebHeaders::ContentLength => 'contentLength',
			WebHeaders::ContentType => 'contentType'
		);
		
		foreach ($map as $header => $property) {
			if (isset($this->headers[$header])) {
				$this->{$property} = $this->headers[$header];
			}
		}
	}
}
