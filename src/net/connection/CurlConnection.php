<?php
/**
 * A curl implementation for internet requests.
 */
namespace SiteCatalog\net\connection;
use SiteCatalog\net\WebRequest as WebRequest;
use SiteCatalog\net\HttpWebRequest as HttpWebRequest;
use SiteCatalog\net\WebResponse as WebResponse;
use SiteCatalog\net\HttpWebResponse as HttpWebResponse;

class CurlConnection implements SiteCatalog\net\connection\IConnection {
	/**
	 * The current request object.
	 * @var \SiteCatalog\net\WebRequest
	 */
	private $_request = null;
	
	/**
	 * Initializes the curl-based implementation for an internet request.
	 * 
	 * @param \SiteCatalog\net\WebRequest $request The request-object to base the request on.
	 * @throws \SiteCatalog\core\exceptions\ArgumentNullException
	 */
	public function __construct(WebRequest $request) {
		if ($request === null) {
			throw new \SiteCatalog\core\exceptions\ArgumentNullException('$request');
		}
		
		$this->_request = $request;
	}

	/**
	 * @inheritDoc
	 * @throws \SiteCatalog\core\exceptions\UnsupportedRequestType
	 */
	public function getResponse() {
		$response = $this->_createResponseObject();
		if ($response === null) {
			throw new \SiteCatalog\core\exceptions\UnsupportedRequestType(get_class($this->_request));
		}
		
		return $this->_exec($response);
	}

	/**
	 * Generate a type-specific Web Response based on the current request.
	 * 
	 * @return \SiteCatalog\net\WebResponse
	 */
	private function _createResponseObject() {
		switch (get_class($this->_request)) {
			case HttpWebRequest:
				return new HttpWebResponse();
			default:
				return null;
		}
	}
	
	/**
	 * Construct and execute our curl-request and populate the response object to return.
	 * 
	 * @param \SiteCatalog\net\WebResponse $response
	 */
	private function _exec(WebResponse &$response) {
		// create the curl object
		$ch = curl_init();
		
		// set all mandatory (and customized) options
		$this->_setOptions($ch);
		
		// set any specified headers
		$this->_setHeaders($ch);
		
		// close our curl object
		curl_close($ch);
	}
	
	/**
	 * Handle custom request-headers directly as a curl option.
	 * 
	 * @param [curl resource] $ch The curl resource to add headers to.
	 */
	private function _setHeaders($ch) {
		if ($ch === null) {
			throw new ArgumentNullException('$ch');
		}
		
		$headers = trim((string)$this->_request->headers);
		if (!empty($headers)) {
			$headers = explode("\r\n", $headers);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
	}
	
	/**
	 * Set all necessary curl options for the current request.
	 * 
	 * @param [curl resource] $ch The curl resource to add options to.
	 */
	private function _setOptions($ch) {
		curl_setopt($ch, CURLOPT_URL, $this->_request->requestUri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		
		// add WebRequest and any sub-class-specific options
		$this->_setWebRequestOptions($ch);
		switch (get_class($this->_request)) {
			case HttpWebRequest:
				$this->_setHttpWebRequestOptions($ch);
				break;
		}
	}
	
	/**
	 * Set all necessary curl options for the current HTTP request.
	 * 
	 * @param [curl resource] $ch The curl resource to add options to.
	 */
	private function _setHttpWebRequestOptions($ch) {
		// define which HTTP version to use
		switch ($this->_request->protocolVersion) {
			case '1.1':
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				break;
			case '1.0':
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				break;
			default:
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_NONE);
		}
		
		// set auto-redirect
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, ($this->_request->allowAutoRedirect === true));
		if (!empty($this->_request->maxAutoRedirects) && ($this->_request->maxAutoRedirects > 0)) {
			curl_setopt($ch, CURLOPT_MAXREDIRS, $this->_request->maxAutoRedirects);
		}
	}
	
	/**
	 * Set all necessary curl options for the current Web request.
	 * 
	 * @param [curl resource] $ch The curl resource to add options to.
	 */
	private function _setWebRequestOptions($ch) {
		// set our connection timeout
		$timeout = is_numeric($this->_request->timeout) ? $this->_request->timeout : 30;
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		
		// if we're using a proxy, add the necessary info
		if (!empty($this->_request->proxy)) {
			// @todo: Implement customization options for all proxy settings (port, username, etc)
			curl_setopt($ch, CURLOPT_PROXY, $this->_request->proxy);
		}
	}
}
