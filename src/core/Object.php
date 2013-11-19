<?php
/**
 * Base object-class properties and definitions for all objects to share.
 */

class Object {
	/**
	 * Override to prevent reading class-variables that don't exist.
	 * 
	 * @param string $varName
	 * @throws Exception
	 */
	public function __get($varName) {
		throw new Exception(sprintf('Class-variable %s not defined.', $varName));
	}
	
	/**
	 * Override to prevent setting class-variables that don't exist.
	 * 
	 * @param type $varName
	 * @param type $value
	 * @throws Exception
	 */
	public function __set($varName, $value = null) {
		throw new Exception(sprintf('Class-variable %s not defined.', $varName));
	}
}
