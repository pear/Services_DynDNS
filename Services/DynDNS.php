<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains the Services_DynDNS class
 * 
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 * 
 * @category   Web Services
 * @package    Services_DynDNS
 * @author     Bryan Dunlap <bdunlap@bryandunlap.com>
 * @copyright  2005 Bryan Dunlap
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: DynDNS.php,v 1.1 2005/08/13 14:25:34 bdunlap Exp $
 * @link       http://pear.php.net/package/Services_DynDNS
 */
 
/**
 * Load Services_DynDNS_Request
 */
require_once 'Services/DynDNS/Request.php';

/**
 * Load Services_DynDNS_Response
 */
require_once 'Services/DynDNS/Response.php';

// {{{ constants

/**
 * API Version of this package
 */
define('SERVICES_DYNDNS_API_VERSION', '0.3.1');

// {{{ defaults

/**
 * The host name of the DynDNS web service
 */
define('SERVICES_DYNDNS_DEFAULT_HOST', 'members.dyndns.org');

/**
 * The default HTTP version to use in requests
 */
define('SERVICES_DYNDNS_DEFAULT_HTTP_VERSION', '1.0');

/**
 * The path to the DynDNS web service
 */
define('SERVICES_DYNDNS_DEFAULT_SERVICE', '/nic/update');

/**
 * The default system to use for requests 
 */
define('SERVICES_DYNDNS_DEFAULT_SYSTEM', 'dyndns');

/**
 * The default HTTP request method to use
 */
define('SERVICES_DYNDNS_DEFAULT_REQUEST_TYPE', HTTP_REQUEST_METHOD_GET);

/**
 * Name of the user agent to use in requests
 */
define('SERVICES_DYNDNS_DEFAULT_USER_AGENT', 'Services_DynDNS/' . 
                                             SERVICES_DYNDNS_API_VERSION);

// }}}
// {{{ error codes

/**
 * Unknown error
 */
define('SERVICES_DYNDNS_ERROR', 'unknown');

/**
 * User agent blocked
 */
define('SERVICES_DYNDNS_ERROR_BADAGENT', 'badagent');

/**
 * Username and/or password not valid
 */
define('SERVICES_DYNDNS_ERROR_BADAUTH', 'badauth');

/**
 * Not a recognized DynDNS system
 */
define('SERVICES_DYNDNS_ERROR_BADSYS', 'badsys');

/**
 * Not a credited user account
 */
define('SERVICES_DYNDNS_ERROR_NODONATOR', '!donator');

/**
 * Not a fully qualified domain name
 */
define('SERVICES_DYNDNS_ERROR_NOFQDN', 'notfqdn');

/**
 * Hostname does not exist
 */
define('SERVICES_DYNDNS_ERROR_NOHOST', 'nohost');

/**
 * Hostname does not belong to this user account
 */
define('SERVICES_DYNDNS_ERROR_NOTYOURS', '!yours');

/**
 * Hostname blocked for update abuse
 */
define('SERVICES_DYNDNS_ERROR_ABUSE', 'abuse');

/**
 * Name of the user agent to use in requests
 */
define('SERVICES_DYNDNS_ERROR_NUMHOST', 'numhost');

/**
 * DNS error
 */
define('SERVICES_DYNDNS_ERROR_DNSERROR', 'dnserr');

/**
 * Critical error
 */
define('SERVICES_DYNDNS_ERROR_CRITICAL', '911');

// }}}
// {{{ success codes

/**
 * Update successful
 */
define('SERVICES_DYNDNS_SUCCESS_GOOD', 'good');

/**
 * Update successful - no data changed
 */
define('SERVICES_DYNDNS_SUCCESS_NOCHG', 'nochg');

// }}}
// {{{ parameter values

/**
 * Parameter value for 'off'
 */
define('SERVICES_DYNDNS_PARAM_VALUE_OFF', 'OFF');

/**
 * Parameter value for 'on'
 */
define('SERVICES_DYNDNS_PARAM_VALUE_ON', 'ON');

/**
 * Parameter value for 'no'
 */
define('SERVICES_DYNDNS_PARAM_VALUE_NO', 'NO');

/**
 * Parameter value for 'no change'
 */
define('SERVICES_DYNDNS_PARAM_VALUE_NOCHG', 'NOCHG');

/**
 * Parameter value for 'yes'
 */
define('SERVICES_DYNDNS_PARAM_VALUE_YES', 'YES');

// }}}



// }}}
// {{{ Services_DynDNS

/**
 * A container class with a static service method for sending requests to the
 * DynDNS REST API
 *
 * @category   Web Services
 * @package    Services_DynDNS
 * @author     Bryan Dunlap <bdunlap@bryandunlap.com>
 * @copyright  2005 Bryan Dunlap
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 */
class Services_DynDNS
{

    // {{{ apiVersion()
    
    /**
     * Gets the current API version for the Services_DynDNS package
     *
     * @return string
     * @access public
	 * @static
     */
    function apiVersion()
    {
        return SERVICES_DYNDNS_API_VERSION;
    }
    
    // }}}
    // {{{ getUserAgent()
    
    /**
     * Retrieves the current user agent
     *
     * @return string
     * @access public
     * @static
     */
    function getUserAgent()
    {
        return Services_DynDNS::_userAgent();
    }

    // }}}
    // {{{ setUserAgent()
    
    /**
     * Sets a client-specific user agent
     *
     * @param  string $userAgent  a string containing the user-agent
     * @return void
     * @access public
     * @static
     */
    function setUserAgent($userAgent)
    {
        Services_DynDNS::_userAgent($userAgent);
    }
    
    // }}}
    // {{{ sendRequest()

    /**
     * Sends a DynDNS_Request to the DynDNS REST web service
     *
     * @param  object $request  a DynDNS_Request object
     * @return object A Services_DynDNS_Response object, PEAR_Error on failure
     * @access public
     * @static
     */
    function &sendRequest(&$request)
    {
        if (!is_a($request, 'Services_DynDNS_Request_common')) {
            return PEAR::raiseError("Not a valid instance of " .
                                    "'Services_DynDNS_Request_common'");
        }
        $httpRequest =& $request->build();
        $httpRequest->sendRequest();
        if ($httpRequest->getResponseCode() != '200') {
            return PEAR::raiseError("Unexpected HTTP response code " .
                                    "'{$httpRequest->getResponseCode()}'",
                                    $httpRequest->getResponseCode());
        }
        return new Services_DynDNS_Response($request->getParameter('hostname'),
                                            $request->getParameter('myip'),
                                            $httpRequest->getResponseBody());
    }
    
    // }}}
    // {{{ _userAgent()
    
    /**
     * Provides internal read/write access to the static user agent variable
     *
     * @param  string $value  (optional) a string containing the user-agent
     * @return mixed                     $userAgent if $value is false, or void
     * @access private
     * @static
     */
    function _userAgent($value = false)
    {
        static $userAgent;
        if ($value === false) {
            return $userAgent;
        }
        $userAgent = $value;
    }
    
    // }}}
    
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
