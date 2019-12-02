<?php

namespace App\Tests\Util;

use App\Util\ListeAgentsWebService;
use PHPUnit\Framework\TestCase;

class ListeAgentsWebServiceTest extends TestCase
{

    public function testGetListAgentsByName(){

        $listAgentsWebService = new ListeAgentsWebService();
        $responseListAgents = $listAgentsWebService->getListAgentsByName('MARTIN');

        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseListAgents);
    }

    /**
     * @TODO
     * - ? list of attributes to fill database
     * 
     */
}