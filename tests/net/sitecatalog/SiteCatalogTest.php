<?php
use net\sitecatalog\SiteCatalog;

require(dirname(dirname(dirname(__FILE__))) . '/setup.php');
class SiteCatalogTest extends PHPUnit_Framework_TestCase {
	
	public function testScan() {
		$url = 'http://www.php.net/';
		$sitecatalog = new SiteCatalog($url);
		
		$sitecatalog->scan();
		$this->assertTrue(isset($sitecatalog->tree[$url]));
	}
	
}
