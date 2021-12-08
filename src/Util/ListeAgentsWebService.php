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
    public function getListAgentsByName($name, $universityCode = '') {

        $soapClientListAgent = SoapClients::getInstance($this->WSDL);
        
        $listAgents = new \StdClass(); // Response expected
        if ($soapClientListAgent) {
            try {
                $listAgents = $soapClientListAgent->recupListeAgents([
                    'ParamRecupListeAgents' => [
                        'nomPatronymique' => $name,
                        'codeEtablissement' => $universityCode,
                        'temoinValide' => '',
                        'listeTypeContrat' => ['codeTypeContrat' => '', 'modeGest' => '']
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
     * @param $startObservationDate require datetime with the format Y-m-d
     * @param $endObservationDate require datetime with the format Y-m-d
     */
    public function getListAgentsDueTerm($startObservationDate, $endObservationDate) {

        $soapClientListAgent = SoapClients::getInstance($this->WSDL);
        
        $listAgents = new \StdClass(); // Response expected
        if ($soapClientListAgent) {
            try {
                $listAgents = $soapClientListAgent->recupAgentsEcheance([
                    'ParamAgentEcheance' => [
                        'dateDebutObservation' => $startObservationDate,
                        'dateFinObservation' => $endObservationDate
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }

        return $listAgents;
    }

}