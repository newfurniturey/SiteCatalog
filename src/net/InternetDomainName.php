<?php
/**
 * Creates an interface into the Public Suffix list to help, among other use-cases, determine
 * if a given string is likely to be an addressable domain on the Internet.
 */
namespace net;
use net\connection\CurlConnection;

class InternetDomainName {
	/**
	 * Location of a full list of all public suffixes.
	 */
	private $_publicSuffixAddress = 'http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1';
	
}
