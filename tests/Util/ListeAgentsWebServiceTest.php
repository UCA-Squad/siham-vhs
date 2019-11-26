<?php


namespace App\Tests\Util;


use App\Util\ListeAgentsWebService;
use PHPUnit\Framework\TestCase;

class ListeAgentsWebServiceTest extends TestCase
{

    public function testRecupListeAgents(){

        $listeAgentsWebService = new ListeAgentsWebService();
        $result = $listeAgentsWebService->recupListeAgents(10,10);
        $this->assertEquals(20, $result);
    }

    public function testRecupAgentsEcheance(){

        $listeAgentsWebService = new ListeAgentsWebService();
        $result = $listeAgentsWebService->recupAgentsEcheance(10,10);
        $this->assertEquals(20, $result);
    }

    public function testRecupAgentsModifies(){

        $listeAgentsWebService = new ListeAgentsWebService();
        $result = $listeAgentsWebService->recupAgentsModifies(10,10);
        $this->assertEquals(21, $result);
    }

    public function testRecupAgentsModifParAffect(){

        $listeAgentsWebService = new ListeAgentsWebService();
        $result = $listeAgentsWebService->recupAgentsModifParAffect(10,10);
        $this->assertEquals(20, $result);
    }
}