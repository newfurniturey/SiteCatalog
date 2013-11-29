<?php

// setup a few required vars
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('DOC_ROOT')) {
	define('DOC_ROOT', dirname(dirname(__FILE__)) . '/src');
}

// require the main app's bootstrap file for all of the config-requirements
require_once(DOC_ROOT . '/config/bootstrap.php');
