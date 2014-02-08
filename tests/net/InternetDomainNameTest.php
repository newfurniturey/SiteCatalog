<?php
use net\InternetDomainName;

require(dirname(dirname(__FILE__)) . '/setup.php');
class InternetDomainNameTest extends PHPUnit_Framework_TestCase {
	
	public function testInit() {
		$refreshed = InternetDomainName::initList();
		$this->assertTrue($refreshed);
	}
	
	/**
	 * @depends testInit
	 */
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
	
	/**
	 * @depends testInit
	 */
	public function testSubdomain() {
		$this->assertEquals(InternetDomainName::getSubDomain('example.com'), null);
		$this->assertEquals(InternetDomainName::getSubDomain('a.example.com'), 'a');
		$this->assertEquals(InternetDomainName::getSubDomain('a.b.example.com'), 'a.b');
		$this->assertEquals(InternetDomainName::getSubDomain('b.c.cy'), null);
		$this->assertEquals(InternetDomainName::getSubDomain('a.b.c.cy'), 'a');
		$this->assertEquals(InternetDomainName::getSubDomain('www.city.kobe.jp'), 'www');
	}

	/**
	 * @depends testInit
	 */
	public function testNullInput() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain(null), null);
	}
	
	/**
	 * @depends testInit
	 */
	public function testMixedCase() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('COM'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('example.COM'), 'example.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('WwW.example.COM'), 'example.com');
	}
	
	/**
	 * @depends testInit
	 */
	public function testLeadingDot() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('.com'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('.example'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('.example.com'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('.example.example'), null);
	}
	
	/**
	 * @depends testInit
	 */
	public function testUnlistedTld() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('example'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('example.example'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.example.example'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.example.example'), null);
	}
	
	/**
	 * @depends testInit
	 */
	public function testListedButNonInternetTld() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('local'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('example.local'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.example.local'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.example.local'), null);
	}
	
	/**
	 * @depends testInit
	 */
	public function testTldWithOnlyOneRule() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('biz'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('domain.biz'), 'domain.biz');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.domain.biz'), 'domain.biz');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.domain.biz'), 'domain.biz');
	}
	
	/**
	 * @depends testInit
	 */
	public function testTldWithSomeTwoLevelRules() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('com'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('example.com'), 'example.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.example.com'), 'example.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.example.com'), 'example.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('uk.com'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('example.uk.com'), 'example.uk.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.example.uk.com'), 'example.uk.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.example.uk.com'), 'example.uk.com');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.ac'), 'test.ac');
	}
	
	/**
	 * @depends testInit
	 */
	public function testTldWithOnlyWildcardRule() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('cy'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('c.cy'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.c.cy'), 'b.c.cy');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.c.cy'), 'b.c.cy');
	}
	
	/**
	 * @depends testInit
	 */
	public function testComplexTld() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('jp'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.jp'), 'test.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.test.jp'), 'test.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('ac.jp'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.ac.jp'), 'test.ac.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.test.ac.jp'), 'test.ac.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('kyoto.jp'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.kyoto.jp'), 'test.kyoto.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('ide.kyoto.jp'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.ide.kyoto.jp'), 'b.ide.kyoto.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.ide.kyoto.jp'), 'b.ide.kyoto.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('c.kobe.jp'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.c.kobe.jp'), 'b.c.kobe.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.c.kobe.jp'), 'b.c.kobe.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('city.kobe.jp'), 'city.kobe.jp');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.city.kobe.jp'), 'city.kobe.jp');
	}
	
	/**
	 * @depends testInit
	 */
	public function testTldWithWildcardAndException() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('ck'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.ck'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('b.test.ck'), 'b.test.ck');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('a.b.test.ck'), 'b.test.ck');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.ck'), 'www.ck');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.www.ck'), 'www.ck');
	}
	
	/**
	 * @depends testInit
	 */
	public function testUsK12() {
		$this->assertEquals(InternetDomainName::getTopLevelDomain('us'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.us'), 'test.us');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.test.us'), 'test.us');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('ak.us'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.ak.us'), 'test.ak.us');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.test.ak.us'), 'test.ak.us');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('k12.ak.us'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('test.k12.ak.us'), 'test.k12.ak.us');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.test.k12.ak.us'), 'test.k12.ak.us');
	}
	
	/**
	 * @depends testInit
	 */
	public function testIdnLabels() {
		/*
		$this->assertEquals(InternetDomainName::getTopLevelDomain('食狮.com.cn'), '食狮.com.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('食狮.公司.cn'), '食狮.公司.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.食狮.公司.cn'), '食狮.公司.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('shishi.公司.cn'), 'shishi.公司.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('公司.cn'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('食狮.中国'), '食狮.中国');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.食狮.中国'), '食狮.中国');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('shishi.中国'), 'shishi.中国');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('中国'), null);
		 */
	}
	
	/**
	 * @depends testInit
	 */
	public function testIdnPunycodedLabels() {
		/*
		$this->assertEquals(InternetDomainName::getTopLevelDomain('xn--85x722f.com.cn'), 'xn--85x722f.com.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('xn--85x722f.xn--55qx5d.cn'), 'xn--85x722f.xn--55qx5d.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.xn--85x722f.xn--55qx5d.cn'), 'xn--85x722f.xn--55qx5d.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('shishi.xn--55qx5d.cn'), 'shishi.xn--55qx5d.cn');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('xn--55qx5d.cn'), null);
		$this->assertEquals(InternetDomainName::getTopLevelDomain('xn--85x722f.xn--fiqs8s'), 'xn--85x722f.xn--fiqs8s');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('www.xn--85x722f.xn--fiqs8s'), 'xn--85x722f.xn--fiqs8s');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('shishi.xn--fiqs8s'), 'shishi.xn--fiqs8s');
		$this->assertEquals(InternetDomainName::getTopLevelDomain('xn--fiqs8s'), null);
		 */
	}
}
