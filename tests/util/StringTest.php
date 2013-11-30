<?php
use util\String;

require(dirname(dirname(__FILE__)) . '/setup.php');
class StringTest extends PHPUnit_Framework_TestCase {
	
	public function testParseUrl() {
		$url = 'http://www.php.net/';
		$data = String::parse_url($url);
		$this->assertEquals('http', $data['scheme']);
		$this->assertEquals('www.php.net', $data['host']);
		
		$url2 = 'www.php.net';
		$data2 = String::parse_url($url2);
		$this->assertEquals('http', $data2['scheme']);
		$this->assertEquals('www.php.net', $data2['host']);
		
		$url3 = 'https://www.php.net/search?q=testing';
		$data3 = String::parse_url($url3);
		$this->assertEquals('https', $data3['scheme']);
		$this->assertEquals('www.php.net', $data3['host']);
		$this->assertEquals('q=testing', $data3['query']);
	}
	
}