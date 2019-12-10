<?php

namespace App\Util;

use App\Util\SoapClients;


class ListeAgentsWebService
{
    private $WSDL = '/ListeAgentsWebService/ListeAgentsWebService?wsdl';
    
    public function getListAgentsByName($name) {

        $soapClientListAgent = SoapClients::getInstance($this->WSDL);
        
        $listAgents = new \StdClass(); // Response expected
        if ($soapClientListAgent) {
            try {
                $listAgents = $soapClientListAgent->recupListeAgents([
                    'ParamRecupListeAgents' => [
                        'nomPatronymique' => $name
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }

        return $listAgents;
    }

}