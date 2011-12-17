<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains the Services_DynDNS_Request_statdns class
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
 * @version    CVS: $Id: statdns.php,v 1.1 2005/08/13 14:25:34 bdunlap Exp $
 * @link       http://pear.php.net/package/Services_DynDNS
 */

/**
 * Load Services_DynDNS_Request_common
 */
require_once 'Services/DynDNS/Request/common.php';

/**
 * Encapsulates a 'statdns' request to the DynDND REST API
 *
 * @category   Web Services
 * @package    Services_DynDNS
 * @author     Bryan Dunlap <bdunlap@bryandunlap.com>
 * @copyright  2005 Bryan Dunlap
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 */
class Services_DynDNS_Request_statdns extends Services_DynDNS_Request_common
{

    /**
     * Parameters for the request
     *
     * @var    array
     * @access protected
     */
    var $parameters = array('username' => '',
                            'password' => '',
                            'hostname' => array(),
                            'myip'     => '',
                            'wildcard' => SERVICES_DYNDNS_PARAM_VALUE_OFF,
                            'mx'       => '',
                            'backmx'   => SERVICES_DYNDNS_PARAM_VALUE_OFF);
    
    /**
     * Constructor
     *
     * @return void
     * @access public
     */
    function Services_DynDNS_Request_statdns()
    {
        $this->system = 'statdns';
        parent::initialize();
    }

}

?>
