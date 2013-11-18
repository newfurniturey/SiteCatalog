<?php
use SiteCatalog\util\Profiler as Profiler;

require('setup.php');
class ProfilerTest extends PHPUnit_Framework_TestCase {
	
	public function testStart() {
		// start a new timer
		$this->assertTrue(Profiler::start('test-timer'));
		
		// try to start the same timer again
		$this->assertFalse(Profiler::start('test-timer'));
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
