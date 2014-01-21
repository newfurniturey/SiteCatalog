<?php
use net\InternetDomainName;

require(dirname(dirname(__FILE__)) . '/setup.php');
class InternetDomainNameTest extends PHPUnit_Framework_TestCase {
	
	public function testInit() {
		$refreshed = InternetDomainName::initList();
		$this->assertTrue($refreshed);
	}
	
	public function testSuffixList() {
		$this->assertEquals(InternetDomainName::getPublicSuffix('biz'), null);
		$this->assertEquals(InternetDomainName::getPublicSuffix('domain.biz'), 'biz');
		$this->assertEquals(InternetDomainName::getPublicSuffix('b.domain.biz'), 'biz');
		$this->assertEquals(InternetDomainName::getPublicSuffix('a.b.domain.biz'), 'biz');
		$this->assertEquals(InternetDomainName::getPublicSuffix('example.com'), 'com');
		$this->assertEquals(InternetDomainName::getPublicSuffix('b.example.com'), 'com');
		$this->assertEquals(InternetDomainName::getPublicSuffix('a.b.example.com'), 'com');
		$this->assertEquals(InternetDomainName::getPublicSuffix('uk.com'), null);
		$this->assertEquals(InternetDomainName::getPublicSuffix('example.uk.com'), 'uk.com');
		$this->assertEquals(InternetDomainName::getPublicSuffix('b.example.uk.com'), 'uk.com');
		$this->assertEquals(InternetDomainName::getPublicSuffix('a.b.example.uk.com'), 'uk.com');
		$this->assertEquals(InternetDomainName::getPublicSuffix('test.ac'), 'ac');
	}
}
