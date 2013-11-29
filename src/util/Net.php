<?php
/**
 * Provides a static interface for net-related utilities.
 */
namespace util;
use net\WebHeaderCollection;
use net\WebHeaders;

class Net {
	
	/**
	 * Converts a web-response style string of headers, "\r\n" delimited, into a WebHeaderCollection.
	 * 
	 * @param string $strHeaders                    Multi-line string of headers to process.
	 * @return \net\WebHeaderCollection Populated collection of headers.
	 */
	public static function ProcessHeaders($strHeaders) {
		$headerCollection = new WebHeaderCollection();
		if (empty($strHeaders)) {
			return $headerCollection;
		}
		
		$headers = explode("\r\n", trim($strHeaders));
		foreach ($headers as $header) {
			if (strpos($header, ':') !== false) {
				// process the name:value header
				list($name, $value) = explode(':', $header, 2);
				$headerCollection[trim($name)] = trim($value);
			} else if (preg_match('/^HTTP\/(1\.[\d])\s+([\d]{3})\s+(.*)?$/i', $header)) {
				// we have our http status code (with the protocol & message)
				$headerCollection[WebHeaders::Status] = $header;
			}
		}
		return $headerCollection;
	}
	
}
