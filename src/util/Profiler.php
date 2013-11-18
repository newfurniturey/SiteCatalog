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
	 * Calculates and returns stat-data for the requested timer.
	 *
	 * @param string $timer The name of the timer to get info for.
	 * @return mixed        Aggregate data from the current timer; false if no timer exists.
	 */
	public static function get($timer) {
		if (!isset(self::$_timers[$timer])) {
			return false;
		}
		
		// get the timer's main info
		$timer = &self::$_timers[$timer];
		$total_duration = $timer['total_duration'];
		$count = $timer['num_starts'];
		
		// calculate the average running-time
		$mean = 0.0;
		for ($i = 0; $i < $count; $i++) {
			$run = $timer['runs'][$i];
			$time = (!empty($run['end']) ? $run['end'] : microtime(true)) - $run['start'];
			$mean += (float)$time;
		}
		$mean /= (float)$count;
		
		return compact('total_duration', 'count', 'mean');
	}
	
	/**
	 * Removes the specified timer from the map of existing timers.
	 * 
	 * @param string $timer The name of the timer to remove.
	 */
	public static function remove($timer) {
		unset(self::$_timers[$timer]);
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
	 * @return bool         true if the timer was started; otherwise false
	 */
	public static function start($timer) {
		// make sure we actually have a valid timer name
		if (empty($timer)) {
			return false;
		}
		
		// if the timer doesn't exist yet, create it
		if (!isset(self::$_timers[$timer])) {
			self::reset($timer);
		}
		
		$timer = &self::$_timers[$timer];
		
		// check if the timer is currently running
		$index = $timer['num_starts'] - 1;
		if (($index >= 0) && empty($timer['runs'][$index]['end'])) {
			return false;
		}

		// add the current time as the starting-time for this timer
		$timer['runs'][$timer['num_starts']] = array(
			'start' => microtime(true),
			'end' => false
		);
		$timer['num_starts']++;
		return true;
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
