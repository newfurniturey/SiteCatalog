<?php
use \SiteCatalog\net\WebRequest;
use \SiteCatalog\net\HttpWebRequest;
use \SiteCatalog\net\WebResponse;
use \SiteCatalog\net\HttpWebResponse;

require('setup.php');
class WebRequestTest extends PHPUnit_Framework_TestCase {
	
	public function testCreateRequest() {
		// create the request object
		$uri = 'http://www.php.net';
		$request = new HttpWebRequest($uri);
		
		// make sure it's the correct type/sub-type
		$this->assertTrue($request instanceof WebRequest);
		$this->assertTrue($request instanceof HttpWebRequest);
		
		// check to see if the uri was properly set
		$this->assertEquals($uri, $request->requestUri);
	}
	
	/**
	 * @depends testCreateRequest
	 */
	public function testGetResponse() {
		// create the request object
		$uri = 'http://www.php.net';
		$request = new HttpWebRequest($uri);
		
		// get the response and check to make sure it's the correct type
		$response = $request->getResponse();
		$this->assertTrue($response instanceof WebResponse);
		$this->assertTrue($response instanceof HttpWebResponse);
		
		// see if the request has a response set
		$this->assertTrue($request->haveResponse);
	}
}
