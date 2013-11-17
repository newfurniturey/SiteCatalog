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
		
	}

	/**
	 * Starts the specified timer.
	 *
	 * @param string $timer The name of the timer to start.
	 */
	public static function start($timer) {
		
	}

	/**
	 * Stops the specified timer.
	 *
	 * @param string $timer The name of the timer to stop.
	 */
	public static function stop($timer) {
		
	}
}
