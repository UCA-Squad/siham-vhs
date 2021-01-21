<?php

namespace App\Util;

use App\Util\SoapClients;


class DossierParametrageWebService
{
    private $WSDL = '/DossierParametrageWebService/DossierParametrageWebService?wsdl';

    public function getStructures($startObservationDate, $codeUO = '') {

        $soapClientDossierAgent = SoapClients::getInstance($this->WSDL);
        
        $structures = new \StdClass(); // Response expected
        if ($soapClientDossierAgent) {
            try {
                $structures = $soapClientDossierAgent->recupStructures([
                    'ParamStructure' => [
                        'codeAdministration' => '',
                        'dateObservation' => $startObservationDate,
                        'listeUO' => [
                            'codeUO' => $codeUO,
                            'structureUO' => '',
                            'typeUO' => '',
                        ]
                    ]
                ]);
            } catch (\SoapFault $fault) {
                // ... log it ?
            }
        }
        return $structures;
    }


    
}