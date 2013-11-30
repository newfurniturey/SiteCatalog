<?php
/**
 * Provides a static interface for string-related utilities.
 */
namespace util;

class String {
	
	/**
	 * Advanced parse_url implementation. {@see parse_url()}
	 * 
	 * @param string $url        The URL to parse.
	 * @param int $component     PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER,
	 *                           PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY or PHP_URL_FRAGMENT
	 * @param bool $force_scheme If true, the URL must contain a scheme and if not it will default to `http://`.
	 * @return mixed             false if an invalid URL is specified; an associative array if `$component`
	 *                           is omitted; a string (or int) if `$component` is specified.
	 */
	public static function parse_url($url, $component = -1, $force_scheme = true) {
		$schemePos = strpos($url, '://');
		$queryStringPos = strpos($url, '?');
		
		// if we require a scheme, check to see if we have one *and* that it appears prior to a query-string
		if ($force_scheme && (($schemePos === false) || (($queryStringPos !== false) && ($schemePos > $queryStringPos)))) {
			$url = sprintf('http://%s', $url);
		}
		
		// make sure the query-string, if any, is url-encoded
		if (($queryStringPos !== false) && ((strpos($url, ':', $queryStringPos) !== false) || (strpos($url, '/', $queryStringPos) !== false))) {
			$nonQuery = substr($url, 0, $queryStringPos);
			$query = str_replace(array(':', '/'), array('%2f', '%3a'), substr($url, $queryStringPos));
			$url = sprintf('%s%s', $nonQuery, $query);
		}
		
		$data = parse_url($url, $component);
		return $data;
	}
	
}
