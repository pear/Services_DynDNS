<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Contains the Services_DynDNS_Request class
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
 * @version    CVS: $Id: Request.php,v 1.1 2005/08/13 14:25:34 bdunlap Exp $
 * @link       http://pear.php.net/package/Services_DynDNS
 */

/**
 * Load HTTP_Request
 */
require_once 'HTTP/Request.php';

/**
 * A static factory class used to create DynDNS_Request object instances
 *
 * @category   Web Services
 * @package    Services_DynDNS
 * @author     Bryan Dunlap <bdunlap@bryandunlap.com>
 * @copyright  2005 Bryan Dunlap
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 */
class Services_DynDNS_Request
{
    
    /**
     * Creates a new DynDNS_Request object from the specified request type
     *
     * @param  string    $type        a string containing the type of request
     * @param  string    $parameters  an array containing the parameters for the
     *                                request
     * @return object    a new DynDNS_Request object, PEAR_Error on failure
     * @access public
     * @static
     */
    function factory($type = null, $parameters = array())
    {
        if (is_null($type)) {
            $type = SERVICES_DYNDNS_DEFAULT_SYSTEM;
        }
        $type = basename(strtolower($type));
        @include_once 'Services/DynDNS/Request/' . $type . '.php';
        $class = 'Services_DynDNS_Request_' . $type;
        if (!class_exists($class)) {
            return PEAR::raiseError("'{$class}' doesn't exist");
        }
        $request = new $class();
        foreach ($parameters as $name => $value) {
            $result = $request->setParameter($name, $value);
            if (PEAR::isError($result)) {
                return $result;
            }
        }
        return $request;
    }
        
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */

?>
