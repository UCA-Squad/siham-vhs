<?php

namespace App\Tests\Util;

use App\Util\ListeAgentsWebService;
use PHPUnit\Framework\TestCase;

class ListeAgentsWebServiceTest extends TestCase
{

    public function testGetListAgentsByName(){

        $listAgentsWebService = new ListeAgentsWebService();
        $responseListAgents = $listAgentsWebService->getListAgentsByName($_ENV['SIHAM_WS_NOMUSUEL_TEST']);

        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseListAgents);
    }

    public function testGetListAgentsUpdated(){

        $listAgentsWebService = new ListeAgentsWebService();
        $responseListAgents = $listAgentsWebService->getListAgentsUpdated(date('Y-m-d'));

        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseListAgents);
    }

    public function testGetListAgentsDueTerm(){

        $listAgentsWebService = new ListeAgentsWebService();
        $responseListAgents = $listAgentsWebService->getListAgentsDueTerm(date('Y-m-d'), date('Y-m-d', \strtotime('+60 days')));

        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseListAgents);
    }

    
}