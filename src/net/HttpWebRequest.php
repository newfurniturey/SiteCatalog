<?php
/**
 * Provides a HTTP-specific implementation of the WebRequest class.
 */
namespace SiteCatalog\net;

class HttpWebRequest extends \SiteCatalog\net\WebRequest {
	
	
	/**
	 * @inheritDoc
	 */
	public function getResponse() {
		$response = new HttpWebResponse();
		// @todo: implement
		return $response;
	}

}
