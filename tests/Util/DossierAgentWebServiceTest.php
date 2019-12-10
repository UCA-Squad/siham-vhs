<?php

namespace App\Tests\Util;

use App\Util\DossierAgentWebService;
use PHPUnit\Framework\TestCase;

const MATRICULE_TEST = 'UCA000023150';

class DossierAgentWebServiceTest extends TestCase
{

    public function testGetPersonalData(){

        $dossierAgentWebService = new DossierAgentWebService();
        $responseDossierAgent = $dossierAgentWebService->getPersonalData(MATRICULE_TEST);
        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseDossierAgent);
    }

    public function testGetAdministrativeData(){

        $dossierAgentWebService = new DossierAgentWebService();
        $responseDossierAgent = $dossierAgentWebService->getAdministrativeData(MATRICULE_TEST);
        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseDossierAgent);
    }

 }