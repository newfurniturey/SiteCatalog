<?php
/**
 * Interface to outline the structure required to make an Internet request.
 */
namespace net\connection;
use net\WebRequest;

interface IConnection {
	
	/**
	 * Initializes the connection for the Internet request.
	 * 
	 * @param WebRequest $request The request-object to base the request on.
	 */
	public function __construct(WebRequest $request);
	
	/**
	 * Establishes the connection with the requested URI to build and return a response.
	 * 
	 * @return \net\WebResponse
	 */
	public function getResponse();
	
}
