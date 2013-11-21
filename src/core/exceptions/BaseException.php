<?php
/**
 * Provides a base Exception implementation that requires a message to be populated.
 */
namespace SiteCatalog\core\exceptions;

abstract class BaseException extends \Exception {
	/**
	 * Override the default constructor to require a message.
	 * 
	 * @inheritDoc
	 */
	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}
