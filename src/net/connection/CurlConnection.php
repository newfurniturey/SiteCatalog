<?php
/**
 * A curl implementation for internet requests.
 */
namespace net\connection;
use net\WebRequest;
use net\WebResponse;
use net\HttpWebResponse;
use net\connection\IConnection;
use util\Net;

class CurlConnection implements IConnection {
	/**
	 * The current request object.
	 * @var \net\WebRequest
	 */
	private $_request = null;
	
	/**
	 * Parsed `type` of the `$_request` object based on the return-value of `get_class()`.
	 */
	private $_requestType = null;
	
	/**
	 * Initializes the curl-based implementation for an internet request.
	 * 
	 * @param \net\WebRequest $request The request-object to base the request on.
	 * @throws \core\exceptions\ArgumentNullException
	 */
	public function __construct(WebRequest $request) {
		if ($request === null) {
			throw new \core\exceptions\ArgumentNullException('$request');
		}
		
		$this->_request = $request;
		$this->_requestType = get_class($request);
	}

	/**
	 * @inheritDoc
	 * @throws \core\exceptions\UnsupportedRequestType
	 */
	public function getResponse() {
		$curlResponse = $this->_exec();
		if ($curlResponse['errno']) {
			throw new \core\exceptions\CurlException($curlResponse['errmsg'], $curlResponse['errno']);
		}
		
		$response = $this->_createResponseObject($curlResponse);
		if ($response === null) {
			throw new \core\exceptions\UnsupportedRequestType($this->_requestType);
		}
		$this->_populateResponseProperties($response, $curlResponse);
		return $response;
	}

	/**
	 * Generate a type-specific Web Response based on the current request.
	 * 
	 * @param array $curlResponse           An array containing all response data to use. {@see _exec()}
	 * @return \net\WebResponse The initialized response object.
	 */
	private function _createResponseObject(array $curlResponse) {
		$headers = $this->_processHeaders($curlResponse);
		$contents = substr($curlResponse['content'], $curlResponse['headers']['header_size']);
		switch ($this->_requestType) {
			case 'net\HttpWebRequest':
				return new HttpWebResponse($headers, $contents);
			default:
				return null;
		}
	}
	
	/**
	 * Construct and execute our curl-request and populate the response object to return.
	 * 
	 * @return array A constructed list of all properties from the curl-request.
	 */
	private function _exec() {
		// create the curl object
		$ch = curl_init();
		
		// set all mandatory (and customized) options
		$this->_setOptions($ch);
		
		// set any specified headers
		$this->_setHeaders($ch);
		
		// execute the request and save the response data
		$response = array(
			'content' => curl_exec($ch),
			'headers' => curl_getinfo($ch),
			'errno' => curl_errno($ch),
			'errmsg' => curl_error($ch)
		);
		
		// close our curl object
		curl_close($ch);
		
		return $response;
	}
	
	/**
	 * Post-populates the Web Response object with different properties from the curl response.
	 * 
	 * @param \net\WebResponse $response The Web Response object to populate.
	 * @param array $curlResponse                    An array containing all response data to use. {@see _exec()}
	 */
	private function _populateResponseProperties(WebResponse $response, array $curlResponse) {
		$response->contentType = $curlResponse['headers']['content_type'];
		$response->responseUri = $curlResponse['headers']['url'];
		$response->responseTime = $curlResponse['headers']['total_time'];
		
		// determine the content length
		$contentLength = ($curlResponse['headers']['download_content_length'] > 0) ? $curlResponse['headers']['download_content_length'] : (
			($curlResponse['headers']['size_download'] > 0) ? $curlResponse['headers']['size_download'] : (strlen($curlResponse['content']) - $curlResponse['headers']['header_size'])
		);
		$response->contentLength = ($contentLength > 0) ? $contentLength : 0;
	}
	
	/**
	 * Parse the response to construct our header collection.
	 * 
	 * @param array $curlResponse                   An array containing all response data. {@see _exec()}
	 * @return \net\WebHeaderCollection The constructed header collection; null if no response-data exists.
	 */
	private function _processHeaders(array $curlResponse) {
		if (!empty($curlResponse['content']) && !empty($curlResponse['headers'])) {
			$strHeaders = substr($curlResponse['content'], 0, $curlResponse['headers']['header_size']);
			return Net::ProcessHeaders($strHeaders);
		}
		return null;
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
		switch ($this->_requestType) {
			case 'net\HttpWebRequest':
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
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
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
