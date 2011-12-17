<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains the Services_DynDNS_Response class
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
 * @version    CVS: $Id: Response.php,v 1.2 2005/08/13 14:32:16 bdunlap Exp $
 * @link       http://pear.php.net/package/Services_DynDNS
 */

// {{{ Services_DynDNS_Response

/**
 * Encapsulates a response (or collection of responses) returned 
 * from the DynDNS REST API
 *
 * @category   Web Services
 * @package    Services_DynDNS
 * @author     Bryan Dunlap <bdunlap@bryandunlap.com>
 * @copyright  2005 Bryan Dunlap
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 */
class Services_DynDNS_Response
{
    
    // {{{ properties
    
    /**
     * IP Address that was part of the initial request
     *
     * @var    string
     * @access private
     */
    var $_requestedIP;
    
    /**
     * DynDNS error codes and custom error messages
     *
     * @var    array
     * @access private
     */
    var $_errorCodes   = array(SERVICES_DYNDNS_ERROR           => 'unknown',
                               SERVICES_DYNDNS_ERROR_BADAGENT  => 'bad user agent',
                               SERVICES_DYNDNS_ERROR_BADAUTH   => 'bad username and/or password',
                               SERVICES_DYNDNS_ERROR_BADSYS    => 'bad system type',
                               SERVICES_DYNDNS_ERROR_NODONATOR => 'not a credited user',
                               SERVICES_DYNDNS_ERROR_NOFQDN    => 'not a fqdn',
                               SERVICES_DYNDNS_ERROR_NOHOST    => 'hostname not found',
                               SERVICES_DYNDNS_ERROR_NOTYOURS  => 'hostname not with user account',
                               SERVICES_DYNDNS_ERROR_ABUSE     => 'hostname blocked for abuse',
                               SERVICES_DYNDNS_ERROR_NUMHOST   => 'too many or too few hosts',
                               SERVICES_DYNDNS_ERROR_DNSERROR  => 'dns error',
                               SERVICES_DYNDNS_ERROR_CRITICAL  => 'critical error');
    
    /**
     * DynDNS success codes and custom success messages
     *
     * @var    array
     * @access private
     */
    var $_successCodes = array(SERVICES_DYNDNS_SUCCESS_GOOD    => 'update successful',
                               SERVICES_DYNDNS_SUCCESS_NOCHG   => 'update successful - no change');
    
    /**
     * Response(s) from the DynDNS REST API
     *
     * @var    array
     * @access private
     */
    var $_parts = array();
    
    // }}}
    // {{{ constructor
    
    /**
     * Constructor
     *
     * @param string $hostname          the string containing the hostname
     * @param string $ipAddress         the string containing the ip address
     * @param string $httpResponseBody  the string containing the
     * 									http response body
     * @return void
     * @access public
     */
    function Services_DynDNS_Response($hostname, $ipAddress, $httpResponseBody)
    {
        $hostnameParts = explode(',', $hostname);
        $this->_requestedIP = $ipAddress;
        $responseBodyParts = explode("\n", $httpResponseBody);
        $count = count($hostnameParts);
        for ($i = 0; $i < $count; $i++) {
            $this->_parts[$i]['hostname'] = $hostnameParts[$i];
            $this->_parts[$i]['responseBody'] = $responseBodyParts[$i];
        }
    }
    
    // }}}
    // {{{ get()
    
    /**
     * Retrieves the next available response
     *
     * @return array
     * @access public
     */
    function get()
    {
        if (!$response = array_shift($this->_parts)) {
            return false;
        }
        $responseBodyParts = explode(' ', $response['responseBody']);
        $current = array();
        $current['hostname'] = $response['hostname'];
        $current['ipAddress'] = isset($responseBodyParts[1]) ?
                                $responseBodyParts[1] : $this->_requestedIP;
        $current['code'] = $responseBodyParts[0];
        $current['success'] = array_key_exists($current['code'], 
                                               $this->_errorCodes) ?
                                               false : true;
        $current['message'] = $current['success'] ?
                              $this->_successCodes[$current['code']] :
                              $this->_errorCodes[$current['code']];
        return $current;
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
