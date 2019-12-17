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

    public function setPersonalData($matricule, $typeNumero, $numero, $typeAction) {

        $soapClientDossierAgent = SoapClients::getInstance($this->WSDL);
        
        $responsePersonalData = new \StdClass(); // Response expected
        if ($soapClientDossierAgent) {
            try {
                $responsePersonalData = $soapClientDossierAgent->modifDonneesPersonnelles([
                    'ParamModifDP' => [
                        'matricule' => $matricule,
                        'typeNumero' => $typeNumero,
                        'numero' => $numero,
                        'typeAction' => $typeAction,
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }
        return isset($responsePersonalData->return) && isset($responsePersonalData->return->statutMAJ) ? $responsePersonalData->return->statutMAJ : false;
    }

    public function addPhonePro($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'TPR', $phoneNumber, 'A') == 1;
    }
    public function updatePhonePro($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'TPR', $phoneNumber, 'M') == 1;
    }
    public function removePhonePro($matricule) {
        return $this->setPersonalData($matricule, 'TPR', '', 'S') == 1;
    }

    public function addMobilePerso($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'PPE', $phoneNumber, 'A') == 1;
    }
    public function updateMobilePerso($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'PPE', $phoneNumber, 'M') == 1;
    }
    public function removeMobilePerso($matricule) {
        return $this->setPersonalData($matricule, 'PPE', '', 'S') == 1;
    }

    public function addEmailPro($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPR', $email, 'A') == 1;
    }
    public function updateEmailPro($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPR', $email, 'M') == 1;
    }
    public function removeEmailPro($matricule) {
        return $this->setPersonalData($matricule, 'MPR', '', 'S') == 1;
    }

    public function addEmailPerso($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPE', $email, 'A') == 1;
    }
    public function updateEmailPerso($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPE', $email, 'M') == 1;
    }
    public function removeEmailPerso($matricule) {
        return $this->setPersonalData($matricule, 'MPE', '', 'S') == 1;
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