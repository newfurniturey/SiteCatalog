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
	
	public function testGet() {
		// clear any existing test-timer so we can start fresh
		Profiler::remove('test-timer');
		
		// start and stop a handful of times
		$timerName = 'test-timer';
		$numRuns = 5;
		$sleep = 1;
		for ($i = 0; $i < $numRuns; $i++) {
			Profiler::start($timerName);
			sleep($sleep);
			Profiler::stop($timerName);
		}
		
		// get our info!
		$info = Profiler::get($timerName);
		
		// check to make sure we got an array back
		$this->assertInternalType('array', $info);
		
		// make sure the number of runs is how many times we ran it
		$this->assertEquals($numRuns, $info['count']);
		
		// do a rudimentary check to see if the duration is close to what it should be
		$this->assertTrue(($numRuns * $sleep) <= ceil($info['total_duration']));
		
		// get info from a timer that doesn't exist
		$this->assertFalse(Profiler::get('iDon\'tExist'));
	}
}
