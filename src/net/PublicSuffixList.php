<?php
/**
 * Manages the full list of Public Suffixes.
 * 
 * @todo Implement file (or database) caching for the list.
 */
namespace net;

class PublicSuffixList {
	/**
	 * Hash-like array of all loaded Public Suffixes.
	 */
	private static $_listHash = array();
	
	/**
	 * Tree array of all loaded Public Suffixes.
	 */
	private static $_listTree = array();
	
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
			$cleanedContents = self::_cleanListContents($listContents);
			self::_parseListIntoHash($cleanedContents);
			self::_parseListIntoTree($cleanedContents);
			return true;
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
			$request = new HttpWebRequest(self::$_publicSuffixAddress);
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
		self::$_listHash = array();
		foreach ($listContents as $node) {
			$firstChar = substr($node, 0, 1);
			if (($firstChar === '*') || ($firstChar === '!')) {
				self::$_listHash[substr($node, 2)] = true;
			} else {
				self::$_listHash[$node] = true;
			}
		}
	}
	
	/**
	 * Converts the Public Suffix list into a top-level-down tree.
	 * 
	 * @param array $listContents An array of every Public Suffix.
	 */
	private static function _parseListIntoTree(array $listContents) {
		self::$_listTree = array();
		foreach ($listContents as $node) {
			$nodeParts = array_reverse(explode('.', $node));
			
			$top = &self::$_listTree;
			foreach ($nodeParts as $nodePart) {
				if (substr($nodePart, 0, 1) === '!') {
					// remove the exception rule but keep the subdomain name
					$nodePart = substr($nodePart, 1);
				}
				
				if (!isset($top[$nodePart])) {
					// add the domain to the tree
					$top[$nodePart] = array();
				}
				$top = &$top[$nodePart];
			}
			$top = &self::$_listTree[array_pop($nodeParts)];
		}
	}
}
