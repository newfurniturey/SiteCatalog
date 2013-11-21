<?php
/**
 * Provides a HTTP-specific implementation of the WebRequest class.
 * 
 * @todo: Add cookie management.
 * @todo: Split curl-usage into a separate interface to hide the implementation.
 */
namespace SiteCatalog\net;
use SiteCatalog\net\WebHeaders as WebHeaders;

class HttpWebRequest extends \SiteCatalog\net\WebRequest {
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
	 * Indicates whether a response has been received from an Internet resource.
	 */
	public $haveResponse = false;
	
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
	public $maximumAutomaticRedirections = 20;
	
	/**
	 * The version of HTTP to use for the request.
	 */
	public $protocolVersion = null;
	
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
	 */
	public function getResponse() {
		if (empty($this->requestUri)) {
			// @todo: Implement better (or actual) URI validation
			throw new \UnexpectedValueException("requestUri");
		}
		
		return $this->_getResponse();
	}
	
	/**
	 * Finalizes all request settings and makes the internet request.
	 * 
	 * @return \SiteCatalog\net\HttpWebResponse
	 */
	protected function _getResponse() {
		$this->_setHeaders();
		
		// @todo: Make the "type" of connection app-specified and not hardcoded
		$connection = new CurlConnection($this);
		return $connection->getResponse();
	}
	
	/**
	 * Overwrites any header that was explicitly defined via one of the many
	 * convenience-properties for this HttpWebRequest instance.
	 */
	protected function _setHeaders() {
		static $map = array(
			'accept' => WebHeaders::Accept,
			'connection' => WebHeader::Connection,
			'host' => WebHeaders::Host,
			'expect' => WebHeaders::Expect,
			'date' => WebHeaders::Date,
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
	}
}
