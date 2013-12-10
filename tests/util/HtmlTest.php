<?php
use util\Html;

require(dirname(dirname(__FILE__)) . '/setup.php');
class HtmlTest extends PHPUnit_Framework_TestCase {
	
	public function testLoadFromFile() {
		$path = $this->_getHtml('plain', true);
		
		$html = new Html();
		$loaded = $html->loadHTMLFile($path);
		
		$this->assertEquals(true, $loaded);
	}
	
	public function testLoadFromString() {
		$contents = $this->_getHtml('plain');
		
		$html = new Html();
		$loaded = $html->loadHTML($contents);
		
		$this->assertEquals(true, $loaded);
	}
	
	public function testParseLinks() {
		$path = $this->_getHtml('links', true);
		
		$html = new Html();
		$loaded = $html->loadHTMLFile($path);
		
		$this->assertEquals(true, $loaded);
		
		$links = $html->getContainedUrls(true);
		$this->assertInternalType('array', $links);
		$this->assertTrue(count($links) > 0);
		
		$comments = $html->query('//comment()');
		foreach ($comments as $comment) {
			$chtml = new Html();
			if ($chtml->loadHTML($comment->nodeValue)) {
				$links = array_merge($links, $chtml->getContainedUrls(true));
			}
		}
	}
	
	private function _getHtml($type, $returnFileName = false) {
		$path = sprintf('%s/util/HtmlTestDocuments/%s.html', TESTS_ROOT, $type);
		if (!file_exists($path)) {
			throw new \InvalidArgumentException('$type');
		}
		return $returnFileName ? $path : file_get_contents($path);
	}
}