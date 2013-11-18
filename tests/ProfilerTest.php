<?php
use SiteCatalog\util\Profiler as Profiler;

require('setup.php');
class ProfilerTest extends PHPUnit_Framework_TestCase {
	
	public function testStart() {
		Profiler::start('test-timer');
		$this->assertTrue(true);
	}
	
	/**
	 * @depends testStart
	 */
	public function testStop() {
		// stop a, what should be, existing timer
		$this->assertTrue(Profiler::stop('test-timer'));
		
		// try to stop (and fail) the same timer
		$this->assertFalse(Profiler::stop('test-timer'));
		
		// try to stop (and fail) a non-existent timer
		$this->assertFalse(Profiler::stop('iDon\'tExist'));
	}
}
