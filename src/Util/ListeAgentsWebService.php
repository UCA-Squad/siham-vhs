<?php

namespace App\Util;

use App\Util\SoapClients;


class ListeAgentsWebService
{
    private $WSDL = '/ListeAgentsWebService/ListeAgentsWebService?wsdl';
    
    /**
     * Get a list of agents filter by name
     * @param $name string, add the character % for inclusive research
     */
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

    /**
     * Get list of agents updated from a date
     * @param $dateObservation require datetime with the format Y-m-d
     */
    public function getListAgentsUpdated($dateObservation) {

        $soapClientListAgent = SoapClients::getInstance($this->WSDL);
        
        $listAgents = new \StdClass(); // Response expected
        if ($soapClientListAgent) {
            try {
                $listAgents = $soapClientListAgent->recupAgentsModifies([
                    'DateHeureDebObservation' => $dateObservation
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }

        return $listAgents;
    }

        /**
     * Get list of agents due term between two dates
     * @param $dateStartObservation require datetime with the format Y-m-d
     * @param $dateEndObservation require datetime with the format Y-m-d
     */
    public function getListAgentsDueTerm($dateStartObservation, $dateEndObservation) {

        $soapClientListAgent = SoapClients::getInstance($this->WSDL);
        
        $listAgents = new \StdClass(); // Response expected
        if ($soapClientListAgent) {
            try {
                $listAgents = $soapClientListAgent->recupAgentsEcheance([
                    'ParamAgentEcheance' => [
                        'dateDebutObservation' => $dateStartObservation,
                        'dateFinObservation' => $dateEndObservation
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }

        return $listAgents;
    }

}