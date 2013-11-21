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
	private $_request = null;
	
	public function __construct(WebRequest $request) {
		if ($request === null) {
			throw new \SiteCatalog\core\exceptions\ArgumentNullException('$request');
		}
		
		$this->_request = $request;
	}

	public function getResponse() {
		$response = $this->_createResponseObject();
		if ($response === null) {
			throw new \SiteCatalog\core\exceptions\UnsupportedRequestType(get_class($this->_request));
		}
		
		return $response;
	}

	private function _createResponseObject() {
		switch (get_class($this->_request)) {
			case HttpWebRequest:
				return new HttpWebResponse();
			default:
				return null;
		}
	}
}
