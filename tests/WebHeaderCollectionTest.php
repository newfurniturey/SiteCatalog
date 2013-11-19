<?php
use SiteCatalog\net\WebHeaderCollection as WebHeaderCollection;
use SiteCatalog\net\WebHeaders as WebHeaders;

require('setup.php');
class WebHeaderCollectionTest extends PHPUnit_Framework_TestCase {
	
	public function testPopulate() {
		$collection = new WebHeaderCollection();
		
		$collection[WebHeaders::CONTENT_LENGTH] = 157;
		$this->assertEquals(157, $collection[WebHeaders::CONTENT_LENGTH]);
		
		$collection[WebHeaders::REFERER] = 'https://www.google.com';
		$this->assertEquals('https://www.google.com', $collection[WebHeaders::REFERER]);
		
		$collection['X-Custom-Header'] = 'yeah!';
		$this->assertEquals('yeah!', $collection['X-Custom-Header']);
	}
	
}
