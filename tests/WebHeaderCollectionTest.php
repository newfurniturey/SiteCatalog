<?php
use SiteCatalog\net\WebHeaderCollection as WebHeaderCollection;
use SiteCatalog\net\WebHeaders as WebHeaders;

require('setup.php');
class WebHeaderCollectionTest extends PHPUnit_Framework_TestCase {
	
	public function testSetAndGet() {
		$collection = new WebHeaderCollection();
		
		$collection[WebHeaders::ContentLength] = 157;
		$this->assertEquals(157, $collection[WebHeaders::ContentLength]);
		
		$collection[WebHeaders::Referer] = 'https://www.google.com';
		$this->assertEquals('https://www.google.com', $collection[WebHeaders::Referer]);
		
		$collection['X-Custom-Header'] = 'yeah!';
		$this->assertEquals('yeah!', $collection['X-Custom-Header']);
		
		$collection->add('Test-Header', 'test-header-value');
		$this->assertEquals('test-header-value', $collection['Test-Header']);
		$this->assertEquals('test-header-value', $collection->get('Test-Header'));
		
		$collection->set('Test-Header', 'overridden!');
		$this->assertEquals('overridden!', $collection->get('Test-Header'));
	}
	
	public function testClear() {
		$values = array('zero', 'one', 'two', 'three');
		
		// create and populate the headers
		$collection = new WebHeaderCollection();
		foreach ($values as $value => $key) {
			$collection[$key] = $value;
		}
		$this->assertEquals(count($values), count($collection));
		
		// clear the collection and make sure the count is zero
		$collection->clear();
		$this->assertEquals(0, count($collection));
		
		// now test removing
		$collection['Test-Header'] = 'isset';
		$this->assertEquals(1, count($collection));
		$collection->remove('Test-Header');
		$this->assertEquals(0, count($collection));
		
		// now test unset
		$collection['Test-Header'] = 'isset';
		$this->assertEquals(1, count($collection));
		unset($collection['Test-Header']);
		$this->assertEquals(0, count($collection));
	}
	
	public function testIterator() {
		$values = array('zero', 'one', 'two', 'three');
		
		// create and populate the headers
		$collection = new WebHeaderCollection();
		foreach ($values as $value => $key) {
			$collection[$key] = $value;
		}
		
		// make sure the counts match up
		$this->assertEquals(count($values), count($collection));

		// do a foreach and make sure it accesses in-order
		$index = 0;
		foreach ($collection as $value => $key) {
			$this->assertEquals($index, $key);
			$this->assertEquals($values[$index], $value);
			$index++;
		}
	}
}
