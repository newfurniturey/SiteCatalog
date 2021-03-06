<?php
/**
 * Creates an interface into the Public Suffix list to help, among other use-cases, determine
 * if a given string is likely to be an addressable domain on the Internet.
 */
namespace net;

class InternetDomainName extends PublicSuffixList {
	
	/**
	 * Returns the Public Suffix of the given host.
	 * 
	 * @param string $host Host to get the Public Suffix for.
	 * @return string      Public Suffix, if found.
	 */
	public static function getPublicSuffix($host) {
		if (!strpos($host, '.')) {
			// if there is no `.` *or* if the first `.` is the leading character, reject immediately
			return null;
		}
		
		$publicSuffix = array();
		$hostParts = array_reverse(explode('.', strtolower($host)));
		$top = &static::$_listTree;
		while (($hostPart = array_shift($hostParts))) {
			if (!isset($top[$hostPart])) {
				// current part is not within the tree; check if we have a wildcard
				if (isset($top['*'])) {
					// we have a wildcard so the current part is part of the suffix
					$publicSuffix[] = $hostPart;
				}
				break;
			} else if (isset($top[$hostPart]['!'])) {
				// stop processing further parts when an exception rule is found
				break;
			}
			$publicSuffix[] = $hostPart;
			$top = &$top[$hostPart];
		}
		
		$result = empty($publicSuffix) ? null : implode('.', array_reverse($publicSuffix));
		return ($result === $host) ? null : $result;
	}
	
	/**
	 * Returns the sub-domain portion of the given host which is the counter-value
	 * of InternetDomainName::getTopLevelDomain()
	 * 
	 * @param string $host Host to get the subdomain for.
	 * @return string      Subdomain, if found.
	 */
	public static function getSubDomain($host) {
		$topLevelDomain = static::getTopLevelDomain($host);
		if (($topLevelDomain === null) || ($topLevelDomain === $host)) {
			return null;
		}
		
		return substr($host, 0, strlen($host) - strlen($topLevelDomain) - 1);
	}
	
	/**
	 * Returns the top-most domain of the given host including the Public Suffix.
	 * 
	 * @param string $host Host to get the TLD for.
	 * @return string      TLD, if found.
	 */
	public static function getTopLevelDomain($host) {
		$publicSuffix = static::getPublicSuffix($host);
		if (($publicSuffix === null) || ($host === $publicSuffix)) {
			return null;
		}

		$hostParts = explode('.', strtolower(substr($host, 0, strlen($host) - strlen($publicSuffix) - 1)));
		return sprintf('%s.%s', end($hostParts), $publicSuffix);
	}
	
	/**
	 * Checks whether a given host is a valid domain or not.
	 * 
	 * @param string $host The host to check.
	 * @return boolean     true if the host is in fact a hostname; otherwise false.
	 */
	public static function isValidDomain($host) {
		if (static::getPublicSuffix($host) !== null) {
			// the host has a valid public suffix; let's verify it's overall-valid
			$host = strtolower($host);
			return (strlen($host) <= 253) && preg_match('/^([a-z\d](-*[a-z\d])*\.?)*$/', $host);
		}
		return false;
	}
}
