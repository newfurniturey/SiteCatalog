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
			}
			$publicSuffix[] = $hostPart;
			$top = &$top[$hostPart];
		}
		
		$result = implode('.', array_reverse($publicSuffix));
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
		return null;
	}
	
	/**
	 * Returns the top-most domain of the given host including the Public Suffix.
	 * 
	 * @param string $host Host to get the TLD for.
	 * @return string      TLD, if found.
	 */
	public static function getTopLevelDomain($host) {
		return null;
	}
	
}
