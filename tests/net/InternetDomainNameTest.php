<?php
use net\InternetDomainName;

require(dirname(dirname(__FILE__)) . '/setup.php');
class InternetDomainNameTest extends PHPUnit_Framework_TestCase {
	
	public function testInit() {
		$refreshed = InternetDomainName::initList();
		$this->assertTrue($refreshed);
	}
	
}
