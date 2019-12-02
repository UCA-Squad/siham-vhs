<?php

namespace App\Util;

use App\Util\SoapClients;

const WSDL = '/ListeAgentsWebService/ListeAgentsWebService?wsdl';

class ListeAgentsWebService
{

    public function getListAgentsByName($name) {

        $soapClientListeAgent = SoapClients::getInstance(WSDL);
        
        return $soapClientListeAgent->recupListeAgents([
			'ParamRecupListeAgents' => [
				'nomPatronymique' => $name
			]
		]);
    }

}