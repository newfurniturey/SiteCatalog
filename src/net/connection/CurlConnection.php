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
		
		return $response;
	}

	/**
	 * Generate a type-specific Web Response based on the current request.
	 * 
	 * @return mixed
	 */
	private function _createResponseObject() {
		switch (get_class($this->_request)) {
			case HttpWebRequest:
				return new HttpWebResponse();
			default:
				return null;
		}
	}
}
