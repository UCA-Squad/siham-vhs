<?php

namespace App\Util;

use App\Util\SoapClients;


class DossierAgentWebService
{
    private $WSDL = '/DossierAgentWebService/DossierAgentWebService?wsdl';

    public function getPersonalData($matricule) {

        $soapClientDossierAgent = SoapClients::getInstance($this->WSDL);
        
        $personalData = new \StdClass(); // Response expected
        if ($soapClientDossierAgent) {
            try {
                $personalData = $soapClientDossierAgent->recupDonneesPersonnelles([
                    'ParamListAgent' => [
                        'dateObservation' => date('c'),
                        'listeMatricules' => [
                            'matricule' => $matricule
                        ]
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }
        return $personalData;
    }

    public function getAdministrativeData($matricule) {

        $soapClientDossierAgent = SoapClients::getInstance($this->WSDL);
        
        $administrativeData = new \StdClass(); // Response expected
        if ($soapClientDossierAgent) {
            try {
                $administrativeData = $soapClientDossierAgent->recupDonneesAdministratives([
                    'ParamListAgent' => [
                        'dateObservation' => date('c'),
                        'listeMatricules' => [
                            'matricule' => $matricule
                        ]
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }
        return $administrativeData;
    }

}