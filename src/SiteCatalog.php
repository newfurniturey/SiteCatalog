<?php
/**
 * SiteCatalog
 * 
 * An automated and interactive tool for cataloging web applications.
 */
namespace SiteCatalog;
use SiteCatalog\util\Profiler as Profiler;

// load the app's config
require('config/bootstrap.php');

// validate that we're running in a console and we have the correct arguments
// @todo: move this to a dedicated utility
if (php_sapi_name() != 'cli') {
	echo 'This application must be executed from a command line.';
	exit(1);
} else if ($argc == 1) {
	echo sprintf("usage: %s domain\n\n", $argv[0]);
	exit(1);
}

// start our primary timer
Profiler::start('SiteCatalog');

// @todo: actually write code =P

// we're done! let's display some stats
if (Profiler::stop('SiteCatalog') && ($runStats = Profiler::get('SiteCatalog'))) {
	echo "\n\n----\n";
	echo sprintf("Completed in %.2fs.\n", $runStats['total_duration']);
}
