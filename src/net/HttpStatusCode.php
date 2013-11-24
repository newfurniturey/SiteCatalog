<?php
/**
 * The values of status codes defined in RFC 2616 for HTTP 1.1.
 */
namespace SiteCatalog\net;

class HttpStatusCode {
	// Informational 1xx
	const Continue100 = 100; // `continue` is a reserved word =[
	const SwitchingProtocols = 101;
	
	// Successful 2xx
	const OK = 200;
	const Created = 201;
	const Accepted = 202;
	const NonAuthorativeInformation = 203;
	const NoContent = 204;
	const ResetContent = 205;
	const PartialContent = 206;
	
	// Redirection 3xx
	const MultipleChoices = 300;
	const MovedPermanently = 301;
	const Found = 302;
	const SeeOther = 303;
	const NotModified = 304;
	const UseProxy = 305;
	const Unused = 306;
	const TemporaryRedirect = 307;
	
	// Client Error 4xx
	const BadRequest = 400;
	const Unauthorized = 401;
	const PaymentRequired = 402;
	const Forbidden = 403;
	const NotFound = 404;
	const MethodNotAllowed = 405;
	const NotAcceptable = 406;
	const ProxyAuthenticationRequired = 407;
	const RequestTimeout = 408;
	const Conflict = 409;
	const Gone = 410;
	const LengthRequired = 411;
	const PreconditionFailed = 412;
	const RequestEntityTooLarge = 413;
	const RequestUriTooLong = 414;
	const UnsupportedMediaType = 415;
	const RequestRangeNotSatisfiable = 416;
	const ExpectationFailed = 417;
	
	// Server Error 5xx
	const InternalServerError = 500;
	const NotImplemented = 501;
	const BadGateway = 502;
	const ServiceUnavailable = 503;
	const GatewayTimeout = 504;
	const HttpVersionNotSupported = 505;
}
