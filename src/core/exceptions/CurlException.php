<?php
/**
 * Exception for arguments that are passed in with null values when a value is required.
 */
namespace SiteCatalog\core\exceptions;

class CurlException extends \SiteCatalog\core\Exceptions\BaseException {
	/**
	 * @inheritDoc
	 */
	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct(sprintf('Curl error: %s', $message), $code, $previous);
	}
}
