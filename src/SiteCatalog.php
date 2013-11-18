<?php
/**
 * SiteCatalog
 * 
 * An automated and interactive tool for cataloging web applications.
 */
namespace SiteCatalog;

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
