<?php
/**
 * Manages the full list of Public Suffixes.
 */
namespace net;
use net\connection\CurlConnection;

class PublicSuffixList {
	/**
	 * Location of a full list of all public suffixes.
	 */
	private static $_publicSuffixAddress = 'http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1';
	
	
}
