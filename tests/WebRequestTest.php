<?php
use net\WebRequest;
use net\HttpWebRequest;
use net\WebResponse;
use net\HttpWebResponse;

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
		
		// check content-length
		$this->assertTrue($response->contentLength > 0);
		$this->assertEquals($response->contentLength, strlen((string)$response));
	}
	
	/**
	 * @depends testGetResponse
	 */
	public function testResponseWithRequestOptions() {
		// create the request object
		$uri = 'http://www.php.net';
		$request = new HttpWebRequest($uri);
		$request->allowAutoRedirect = false;
		
		// get the response and verify that the options we set were translated by the response
		$response = $request->getResponse();
		
		// we disabled auto-redirect so the response-address should be the uri we started with
		$this->assertEquals($uri, $response->responseUri);
		
		// check to see if we actually received contents back
		// @todo make this check "actual" contents when it's implemented
		$this->assertTrue($response->contentLength > 0);
	}
}
