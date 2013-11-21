<?php
/**
 * Interface to outline the structure required to make an Internet request.
 */
namespace SiteCatalog\net\connection;
use SiteCatalog\net\WebRequest as WebRequest;

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
	 * @return \SiteCatalog\net\WebResponse
	 */
	public function getResponse();
	
}
