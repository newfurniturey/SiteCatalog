<?php
/**
 * Core configuration and initialization.
 */

/**
 * Turn on error reporting if it's not already on.
 */
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);

/**
 * Convenience definition for PHP's Directory Separator.
 */
	define('DS', DIRECTORY_SEPARATOR);

/**
 * Convenience definition for this application's Document Root.
 */
	define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
