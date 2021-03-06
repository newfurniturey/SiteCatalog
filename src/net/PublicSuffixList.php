<?php
/**
 * Manages the full list of Public Suffixes.
 * 
 * @todo Implement file (or database) caching for the list.
 */
namespace net;

class PublicSuffixList {
	/**
	 * Flag indicating whether the list is initialized or not.
	 */
	private static $_initialized = false;
	
	/**
	 * Hash-like array of all loaded Public Suffixes.
	 */
	protected static $_listHash = array();
	
	/**
	 * Tree array of all loaded Public Suffixes.
	 */
	protected static $_listTree = array();
	
	/**
	 * Location of a full list of all public suffixes.
	 */
	private static $_publicSuffixAddress = 'http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1';
	
	/**
	 * Loads the remote Public Suffix list and refreshes the internal cache.
	 * 
	 * @return boolean true if the list was refreshed; otherwise false
	 */
	public static function initList() {
		if (PublicSuffixList::$_initialized) {
			return true;
		}
		
		$listContents = static::_fetchList();
		if ($listContents !== null) {
			$cleanedContents = static::_cleanListContents($listContents);
			static::_parseListIntoHash($cleanedContents);
			static::_parseListIntoTree($cleanedContents);
			return (PublicSuffixList::$_initialized = true);
		}
		return false;
	}
	
	/**
	 * Removes negligible lines from the loaded list-contents for faster processing.
	 * 
	 * @param string $listContents The string-version of the Public Suffix list.
	 * @return array               Cleaned array.
	 */
	private static function _cleanListContents($listContents) {
		// convert \r\n to just \n (so we don't have to worry about mixed endings
		$listContents = str_replace("\r\n", "\n", $listContents);
		
		// remove all empty and comment lines from the list
		$listContents = trim(preg_replace('/^(\/\/(.*?))?(\n|$)/m', '', $listContents));
		
		// separate each line into an array
		return explode("\n", $listContents);
	}
	
	/**
	 * Fetches the Suffix List from the currently specified address.
	 * 
	 * @return string The textual contents of the list.
	 */
	private static function _fetchList() {
		try {
			$request = new HttpWebRequest(PublicSuffixList::$_publicSuffixAddress);
			$response = $request->getResponse();
			return $response->contents;
		} catch (\Exception $e) {
			return null;
		}
	}
	
	/**
	 * Converts the Public Suffix list into a distinct hash-type array.
	 * 
	 * This conversion will remove wildcard and exclamation mark suffixes for faster
	 * and more direct lookups.
	 * 
	 * @param array $listContents An array of every Public Suffix.
	 */
	private static function _parseListIntoHash(array $listContents) {
		static::$_listHash = array();
		foreach ($listContents as $node) {
			$firstChar = substr($node, 0, 1);
			if (($firstChar === '*') || ($firstChar === '!')) {
				static::$_listHash[substr($node, 2)] = true;
			} else {
				static::$_listHash[$node] = true;
			}
		}
	}
	
	/**
	 * Converts the Public Suffix list into a top-level-down tree.
	 * 
	 * @param array $listContents An array of every Public Suffix.
	 */
	private static function _parseListIntoTree(array $listContents) {
		static::$_listTree = array();
		foreach ($listContents as $node) {
			$nodeParts = array_reverse(explode('.', $node));
			
			$top = &static::$_listTree;
			foreach ($nodeParts as $nodePart) {
				$isExceptionRule = false;
				if (substr($nodePart, 0, 1) === '!') {
					// remove the exception rule but keep the subdomain name
					$nodePart = substr($nodePart, 1);
					$isExceptionRule = true;
				}
				
				if (!isset($top[$nodePart])) {
					// add the domain to the tree
					$top[$nodePart] = array();
					if ($isExceptionRule) {
						$top[$nodePart]['!'] = array();
					}
				}
				$top = &$top[$nodePart];
			}
			$top = &static::$_listTree[array_pop($nodeParts)];
		}
	}
}
