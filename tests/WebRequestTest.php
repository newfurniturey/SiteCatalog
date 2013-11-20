<?php
use \SiteCatalog\net\WebRequest as WebRequest;
use \SiteCatalog\net\HttpWebRequest as HttpWebRequest;
use \SiteCatalog\net\WebResponse as WebResponse;
use \SiteCatalog\net\HttpWebResponse as HttpWebResponse;

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
