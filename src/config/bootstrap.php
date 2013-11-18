<?php
/**
 * Core configuration and initialization.
 */
namespace SiteCatalog\config;

/**
 * Turn on error reporting if it's not already on.
 */
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);

/**
 * Convenience definition for PHP's Directory Separator.
 */
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}

/**
 * Convenience definition for this application's Document Root.
 */
	if (!defined('DOC_ROOT')) {
		$doc_root = !empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : dirname(dirname(__FILE__));
		define('DOC_ROOT', $doc_root);
	}

/**
 * Register a namespace-path to class-path __autoload function.
 * 
 * @param string $class The namespace-including class name to locate in the application's
 *                      directory structure.
 */
	spl_autoload_register(function ($class) {
		// remove the project's root namespace (SiteCatalog\) from the path
		if (substr($class, 0, 12) == 'SiteCatalog\\') {
			$class = substr($class, 12);
		}
		
		// convert the namespace `\` to the current system's directory separator
		$class = str_replace('\\', DS, $class);
		
		// give the path a .php extension and if the file exists, include it!
		$path = sprintf('%s/%s.php', DOC_ROOT, $class);
		if (file_exists($path)) {
			require_once($path);
		}
	});
