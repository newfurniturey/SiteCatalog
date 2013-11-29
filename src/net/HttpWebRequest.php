<?php
/**
 * Provides a HTTP-specific implementation of the WebRequest class.
 * 
 * @todo: Add cookie management.
 * @todo: Split curl-usage into a separate interface to hide the implementation.
 */
namespace net;
use net\WebHeaders;
use net\connection\CurlConnection;

class HttpWebRequest extends \net\WebRequest {
	/**
	 * Convenience-property for the Accept HTTP header.
	 */
	public $accept = null;
	
	/**
	 * The URI of the Internet resource that actually responded to the request.
	 */
	public $address = null;
	
	/**
	 * Indicates whether the request should follow redirection responses.
	 */
	public $allowAutoRedirect = true;
	
	/**
	 * Convenience-property for the Connection HTTP header.
	 */
	public $connection = null;
	
	/**
	 * Convenience-property for the Date HTTP header.
	 */
	public $date = null;
	
	/**
	 * Convenience-property for the Expect HTTP header.
	 */
	public $expect = null;
	
	/**
	 * Convenience-property for the Host HTTP header, independent from the request URI.
	 */
	public $host = null;
	
	/**
	 * Convenience-property for the If-Modified-Since HTTP header.
	 */
	public $ifModifiedSince = null;
	
	/**
	 * Indicates whether to make a persistent connection to the Internet resource.
	 */
	public $keepAlive = null;
	
	/**
	 * The maximum number of redirects that the request follows.
	 */
	public $maxAutoRedirects = 20;
	
	/**
	 * The version of HTTP to use for the request.
	 * 
	 * Supported versions: 1.0, 1.1
	 */
	public $protocolVersion = '1.1';
	
	/**
	 * Convenience-property for the Referer HTTP header.
	 */
	public $referer = null;
	
	/**
	 * Convenience-property for the Transfer-encoding HTTP header.
	 */
	public $transferEncoding = null;
	
	/**
	 * Convenience-property for the User-agent HTTP header.
	 */
	public $userAgent = null;
	
	/**
	 * @inheritDoc
	 * 
	 * @todo: Does this need to be abstracted out *and* in a sub-class? It could, potentially
	 * go directly in WebRequest...
	 */
	public function getResponse() {
		if (empty($this->requestUri)) {
			// @todo: Implement better (or actual) URI validation
			throw new \UnexpectedValueException("requestUri");
		}
		
		$response = $this->_getResponse();
		if ($response) {
			$this->haveResponse = true;
		}
		return $response;
	}
	
	/**
	 * Finalizes all request settings and makes the internet request.
	 * 
	 * @return \net\HttpWebResponse
	 */
	protected function _getResponse() {
		$this->_setHeaders();
		
		// @todo: Make the "type" of connection app-specified and not hardcoded
		$connection = new CurlConnection($this);
		return $connection->getResponse();
	}
	
	/**
	 * @inheritDoc
	 */
	protected function _setHeaders() {
		parent::_setHeaders();
		static $map = array(
			'accept' => WebHeaders::Accept,
			'connection' => WebHeaders::Connection,
			'date' => WebHeaders::Date,
			'expect' => WebHeaders::Expect,
			'host' => WebHeaders::Host,
			'ifModifiedSince' => WebHeaders::IfModifiedSince,
			'referer' => WebHeaders::Referer,
			'transferEncoding' => WebHeaders::TransferEncoding,
			'userAgent' => WebHeaders::UserAgent
		);
		
		foreach ($map as $property => $header) {
			if (!empty($this->{$property})) {
				$this->headers[$header] = $this->{$property};
			}
		}
		
		// set the keep-alive header if `connection` isn't already specified
		if (!empty($this->keepAlive) && empty($this->connection)) {
			$this->headers['Connection'] = 'Keep-Alive';
		}
	}
}
