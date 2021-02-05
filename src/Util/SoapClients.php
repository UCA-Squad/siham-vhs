<?php

namespace App\Util;

class SoapClients {

    private static $soapInstances = [];
    
    
    /**
     * Get SoapClient soapInstances or instance it
     */
    public static function getInstance($wsdl)
    {
        // check if connection exist
        if (!isset(self::$soapInstances[$wsdl])) {
            try {
                self::$soapInstances[$wsdl] = new \SoapClient($_ENV['SIHAM_WS_URL'] . $wsdl, [
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'login' => $_ENV['SIHAM_WS_USERNAME'],
                    'password' => $_ENV['SIHAM_WS_PASSWORD'],
                    'trace' => true,
                ]);
            } catch (\SoapFault $fault) {
                return false;
            }
        }
        return self::$soapInstances[$wsdl];
    }
}