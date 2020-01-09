<?php

namespace App\Tests\Util;


use App\Util\SoapClients;
use PHPUnit\Framework\TestCase;

class SoapClientsTest extends TestCase {

    /**
     * Instance of ListeAgentsWebService exist or connection success
     */
    public function testConnectionListAgents() {

        $connectionListAgents = SoapClients::getInstance('/ListeAgentsWebService/ListeAgentsWebService?wsdl');

        $this->assertEquals(false, is_bool($connectionListAgents));
    }

    /**
     * Instance of DossierAgentWebService exist or connection success
     */
    public function testConnectionDossierAgent() {

        $connectionDossierAgent = SoapClients::getInstance('/DossierAgentWebService/DossierAgentWebService?wsdl');

        $this->assertEquals(false, is_bool($connectionDossierAgent));
    }

    /**
     * Instance of DossierParametrageWebService exist or connection success
     */
    public function testConnectionDossierParametrage() {

        $connectionDossierParametrage = SoapClients::getInstance('/DossierParametrageWebService/DossierParametrageWebService?wsdl');

        $this->assertEquals(false, is_bool($connectionDossierParametrage));
    }

}