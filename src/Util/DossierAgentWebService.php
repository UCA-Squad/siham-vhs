<?php

namespace App\Util;

use App\Util\SoapClients;


class DossierAgentWebService
{
    private $WSDL = '/DossierAgentWebService/DossierAgentWebService?wsdl';

    public function getPersonalData($matricule, $startObservationDate = null, $endObservationDate = null) {

        $soapClientDossierAgent = SoapClients::getInstance($this->WSDL);
        
        $personalData = new \StdClass(); // Response expected
        if ($soapClientDossierAgent) {
            try {
                $personalData = $soapClientDossierAgent->recupDonneesPersonnelles([
                    'ParamListAgent' => [
                        'dateObservation' => $startObservationDate ?? date('Y-m-d'),
                        'dateFinObservation' => $endObservationDate ?? '',
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

    //region set personal data
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
    public function addPhoneProfessional($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'TPR', $phoneNumber, 'A');
    }
    public function updatePhoneProfessional($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'TPR', $phoneNumber, 'M');
    }
    public function removePhoneProfessional($matricule, $forGenericCalls = '') {
        return $this->setPersonalData($matricule, 'TPR', $forGenericCalls, 'S');
    }

    public function addMobileProfessional($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'PPR', $phoneNumber, 'A');
    }
    public function updateMobileProfessional($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'PPR', $phoneNumber, 'M');
    }
    public function removeMobileProfessional($matricule, $forGenericCalls = '') {
        return $this->setPersonalData($matricule, 'PPR', $forGenericCalls, 'S');
    }

    public function addMobilePersonal($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'PPE', $phoneNumber, 'A');
    }
    public function updateMobilePersonal($matricule, $phoneNumber) {
        return $this->setPersonalData($matricule, 'PPE', $phoneNumber, 'M');
    }
    public function removeMobilePersonal($matricule, $forGenericCalls = '') {
        return $this->setPersonalData($matricule, 'PPE', $forGenericCalls, 'S');
    }

    public function addEmailProfessional($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPR', $email, 'A');
    }
    public function updateEmailProfessional($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPR', $email, 'M');
    }
    public function removeEmailProfessional($matricule, $forGenericCalls = '') {
        return $this->setPersonalData($matricule, 'MPR', $forGenericCalls, 'S');
    }

    public function addEmailPersonal($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPE', $email, 'A');
    }
    public function updateEmailPersonal($matricule, $email) {
        return $this->setPersonalData($matricule, 'MPE', $email, 'M');
    }
    public function removeEmailPersonal($matricule, $forGenericCalls = '') {
        return $this->setPersonalData($matricule, 'MPE', $forGenericCalls, 'S');
    }
    //endregion

    public function getAdministrativeData($matricule, $startObservationDate = null, $endObservationDate = null) {

        $soapClientDossierAgent = SoapClients::getInstance($this->WSDL);
        
        $administrativeData = new \StdClass(); // Response expected
        if ($soapClientDossierAgent) {
            try {
                $administrativeData = $soapClientDossierAgent->recupDonneesAdministratives([
                    'ParamListAgent' => [
                        'dateObservation' => $startObservationDate ?? date('Y-m-d'),
                        'dateFinObservation' => $endObservationDate ?? '',
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