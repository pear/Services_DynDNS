<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains the Services_DynDNS_Request_common class
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
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: common.php,v 1.1 2005/08/13 14:25:34 bdunlap Exp $
 */

// {{{ Services_DynDNS_Response_common

/**
 * An abstract class which provides common functionality for all current types
 * of DynDNS REST API requests
 * 
 * @category   Web Services
 * @package    Services_DynDNS
 * @author     Bryan Dunlap <bdunlap@bryandunlap.com>
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 */
class Services_DynDNS_Request_common
{
    
    // {{{ properties
    
    /**
     * The hostname of the DynDNS web service
     *
     * @var    string
     * @access protected
     */
    var $host;

    /**
     * The path to the DynDNS web service
     *
     * @var    string
     * @access protected
     */
    var $service;

    /**
     * The DynDNS system to use for requests
     *
     * @var    string
     * @access protected
     */
    var $system;

    /**
     * Parameters for the request
     *
     * @var    array
     * @access protected
     */
    var $parameters = array();
    
    // }}}
    // {{{ build()
    
    /**
     * Build and return an HTTP_Request object reflecting the specifics of this
     * particular DynDNS_Request
     *
     * @return object
     * @access public
     */
    function &build()
    {
        $url = 'https://' . $this->host . $this->service;
        $parameters = array('user'     => $this->parameters['username'],
                            'pass'     => $this->parameters['password'],
                            'http'     => SERVICES_DYNDNS_DEFAULT_HTTP_VERSION,
                            'method'   => SERVICES_DYNDNS_DEFAULT_REQUEST_TYPE,
                            'saveBody' => true); 
        $httpRequest =& new HTTP_Request($url, $parameters);
        if (!($userAgent = Services_DynDNS::getUserAgent())) {
            $userAgent = SERVICES_DYNDNS_DEFAULT_USER_AGENT;
        }
        $httpRequest->addHeader('User-Agent', $userAgent);
        $httpRequest->addQueryString('system', $this->system);
        foreach ($this->parameters as $name => $value) {
            if ($name != 'username' && $name != 'password') {
                $httpRequest->addQueryString($name, $value);
            }
        }
        return $httpRequest;
    }
    
    // }}}
    // {{{ addHostname()

    /**
     * Appends a hostname to the end of the 'hostname' parameter string
     *
     * @param string $hostname        a string containing the hostname
     * @return string
     * @access public
     */
    function addHostname($hostname)
    {
        $seperator = $this->parameters['hostname'] ? ',' : '';
        $this->parameters['hostname'] .= $seperator . $hostname;
    }
    
    // }}}
    // {{{ getParameter()

    /**
     * Returns the value for a specific parameter
     *
     * @param string $name        a string containing the name of
     *                            the parameter
     * @return string
     * @access public
     */
    function getParameter($name)
    {
        if (!isset($this->parameters[$name])) {
            return PEAR::raiseError("Parameter '{$name}' " .
                                    "doesn't exist in class " .
                                    "'Services_DynDNS_Request_" .
                                    "{$this->system}'");
        }
        return $this->parameters[$name];
    }

    // }}}
    // {{{ setParameter()

    /**
     * Sets the value for a specific parameter
     *
     * @param string $name        a string containing the name of
     *                            the parameter
     * @param array  $value       a string containing value of 
     *                            the  parameter
     * @return void
     * @access public
     */
    function setParameter($name, $value)
    {
        if (!isset($this->parameters[$name])) {
            return PEAR::raiseError("Parameter '{$name}' " .
                                    "doesn't exist in class " .
                                    "'Services_DynDNS_Request_" .
                                    "{$this->system}'");
        }
        $this->parameters[$name] = $value;
    }
        
    // }}}
    // {{{ initialize()

    /**
     * Sets the default DynDNS host, service and user agent
     *
     * @return void
     * @access protected
     */
    function initialize()
    {
        $this->host      = SERVICES_DYNDNS_DEFAULT_HOST;
        $this->service   = SERVICES_DYNDNS_DEFAULT_SERVICE;
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
