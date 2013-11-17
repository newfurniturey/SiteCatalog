<?php
/**
 * Provides a static interface for timing and profiling events.
 */
namespace SiteCatalog\util;

class Profiler {
	/**
	 * Map of all created timers.
	 */
	private static $_timers = array();
	
	/**
	 * Empty / private constructor.
	 */
	private function __construct() { }

	/**
	 * Retrieves the requested info for the specified timer.
	 *
	 * @param string $timer The name of the timer to get info for.
	 */
	public static function get($timer) {
		
	}
	
	/**
	 * Removes the specified timer from the map of existing timers.
	 * 
	 * @param string $timer The name of the timer to remove.
	 */
	public static function remove($timer) {
		
	}
	
	/**
	 * Resets the specified timer to default values.
	 * 
	 * @param String $timer The name of the timer to reset.
	 */
	public static function reset($timer) {
		self::$_timers[$timer] = array(
			// list of ever run (start/end time) for this timer
			'runs' => array(),
			
			// number of times this timer has been started
			'num_starts' => 0,
			
			// total duration this timer has ran for
			'total_duration' => 0
		);
	}

	/**
	 * Starts the specified timer.
	 *
	 * @param string $timer The name of the timer to start.
	 */
	public static function start($timer) {
		// if the timer doesn't exist yet, create it
		if (!isset(self::$_timers[$timer])) {
			self::reset($timer);
		}
		
		// add the current time as the starting-time for this timer
		self::$_timers[$timer]['runs'][self::$_timers[$timer]['num_starts']] = array(
			'start' => microtime(true),
			'end' => false
		);
		self::$_timers[$timer]['num_starts']++;
	}

	/**
	 * Stops the specified timer.
	 *
	 * @param string $timer The name of the timer to stop.
	 * @return bool         true if the timer was stopped; otherwise false
	 */
	public static function stop($timer) {
		// grab the end-time now so we don't waste time running misc validation
		$endTime = microtime(true);
		
		// check if the timer actually exists
		if (!isset(self::$_timers[$timer])) {
			return false;
		}
		
		$timer = &self::$_timers[$timer];
		$index = $timer['num_starts'] - 1;
		
		// check if the timer is actually running
		if (($index < 0) || !empty($timer['runs'][$index]['end'])) {
			return false;
		}
		
		// stop the timer =]
		$timer['runs'][$index]['end'] = $endTime;
		$timer['total_duration'] += ($endTime - $timer['runs'][$index]['start']);
		return true;
	}
}
