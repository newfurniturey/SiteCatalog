<?php

// setup a few required vars
define('DS', DIRECTORY_SEPARATOR);
define('DOC_ROOT', dirname(dirname(__FILE__)) . '/src');

// require the main app's bootstrap file for all of the config-requirements
require(DOC_ROOT . '/config/bootstrap.php');
