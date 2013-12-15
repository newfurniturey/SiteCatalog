<?php
/**
 * Manages the full list of Public Suffixes.
 * 
 * @todo Implement file (or database) caching for the list.
 */
namespace net;
use net\connection\CurlConnection;

class PublicSuffixList {
	/**
	 * Hash-like array of all loaded Public Suffixes.
	 */
	private static $_list = array();
	
	/**
	 * Location of a full list of all public suffixes.
	 */
	private static $_publicSuffixAddress = 'http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1';
	
	/**
	 * Loads the remote Public Suffix list and refreshes the internal cache.
	 * 
	 * @return boolean true if the list was refreshed; otherwise false
	 */
	public static function refreshList() {
		$listContents = self::_fetchList();
		if ($listContents !== null) {
			self::$_list = self::_parseListIntoHash($listContents);
			return true;
		}
		return false;
	}
	
	/**
	 * Fetches the Suffix List from the currently specified address.
	 * 
	 * @return string The textual contents of the list.
	 */
	private static function _fetchList() {
		try {
			$request = new HttpWebRequest(self::$_publicSuffixAddress);
			$response = $request->getResponse();
			return $response->contents;
		} catch (\Exception $e) {
			return null;
		}
	}
	
	/**
	 * Parses the string-version of the list into a hash-style array for fast lookups.
	 * 
	 * @param string $listContents The string-version of the Public Suffix list.
	 * @return array               Converted array.
	 */
	private static function _parseListIntoHash($listContents) {
		// convert \r\n to just \n (so we don't have to worry about mixed endings
		$listContents = str_replace("\r\n", "\n", $listContents);
		
		// remove all empty and comment lines from the list
		$listContents = trim(preg_replace('/^([^a-z].*)?\n/', '', $listContents));
		
		// make us a list!
		return array_flip(explode("\n", $listContents));
	}
}
