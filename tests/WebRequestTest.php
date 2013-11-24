<?php
use \SiteCatalog\net\WebRequest;
use \SiteCatalog\net\HttpWebRequest;
use \SiteCatalog\net\WebResponse;
use \SiteCatalog\net\HttpWebResponse;

require('setup.php');
class WebRequestTest extends PHPUnit_Framework_TestCase {
	
	public function testGetResponse() {
		$uri = 'http://www.php.net';
		$request = new HttpWebRequest($uri);
		$this->assertTrue($request instanceof WebRequest);
		$this->assertTrue($request instanceof HttpWebRequest);
		
		$response = $request->getResponse();
		$this->assertTrue($response instanceof WebResponse);
		$this->assertTrue($response instanceof HttpWebResponse);
	}
	
}
