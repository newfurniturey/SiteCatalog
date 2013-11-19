<?php
/**
 * Hash-like collection that contains the protocol headers associated with a web request or response.
 */
namespace SiteCatalog\net;

class WebHeaderCollection extends \SiteCatalog\core\Object implements \ArrayAccess, \Countable, \Iterator {
	/**
	 * A key/value pair list of each defined header in the current collection.
	 */
	private $_headers = array();
	
	/**
	 * Inserts a header with the specified name and value into the collection.
	 * 
	 * @param string $header Name of the header to insert.
	 * @param string $value  Value to assign to the header.
	 */
	public function add($header, $value) {
		$this[$header] = $value;
	}
	
	/**
	 * Removes all headers from the collection.
	 */
	public function clear() {
		$this->_headers = array();
	}
	
	/**
	 * Count elements of an object.
	 * 
	 * @inheritDoc
	 */
	public function count() {
		return count($this->_headers);
	}
	
	/**
	 * Return the current element.
	 * 
	 * @inheritDoc
	 */
	public function current() {
		return current($this->_headers);
	}
	
	/**
	 * Get the value of the specified header in the collection.
	 * 
	 * @param string $header Name of the header to return the value of.
	 * @return string        Value of the header; null if not defined.
	 */
	public function get($header) {
		return $this[$header];
	}
	
	/**
	 * Return the key of the current element.
	 * 
	 * @inheritDoc
	 */
	public function key() {
		return key($this->_headers);
	}
	
	/**
	 * Move forward to the next element.
	 * 
	 * @inheritDoc
	 */
	public function next() {
		return next($this->_headers);
	}
		
	/**
	 * Whether an offset exists.
	 * 
	 * @inheritDoc
	 */
	public function offsetExists($offset) {
		return isset($this->_headers[$offset]);
	}
	/**
	 * Offset to retrieve.
	 * 
	 * @inheritDoc
	 */
	public function offsetGet($offset) {
		return isset($this->_headers[$offset]) ? $this->_headers[$offset] : null;
	}
	/**
	 * Offset to set.
	 * 
	 * @inheritDoc
	 */
	public function offsetSet($offset, $value) {
		if (is_null($offset) || (($offset = trim($offset)) === '')) {
			return;
		}
		$this->_headers[$offset] = $value;
	}
	/**
	 * Offset to unset.
	 * 
	 * @inheritDoc
	 */
	public function offsetUnset($offset) {
		unset($this->_headers[$offset]);
	}
	
	/**
	 * Removes the specified header from the collection.
	 * 
	 * @param string $header Name of the header to remove.
	 */
	public function remove($header) {
		unset($this[$header]);
	}
	
	/**
	 * Rewind the iterator to the first element.
	 * 
	 * @inheritDoc
	 */
	public function rewind() {
		reset($this->_headers);
	}
	
	/**
	 * Sets the specified header to the specified value.
	 * 
	 * @param string $header Name of the header to set.
	 * @param string $value  Value to assign to the header.
	 */
	public function set($header, $value) {
		$this[$header] = $value;
	}
	
	/**
	 * Checks if the current position is valid
	 * 
	 * @inheritDoc
	 */
	public function valid() {
		return (key($this->_headers) !== null);
	}
}
