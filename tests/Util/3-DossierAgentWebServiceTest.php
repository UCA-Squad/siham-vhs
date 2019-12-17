<?php

namespace App\Tests\Util;

use App\Util\DossierAgentWebService;
use PHPUnit\Framework\TestCase;

class DossierAgentWebServiceTest extends TestCase
{

    public function testGetPersonalData(){

        $dossierAgentWebService = new DossierAgentWebService();
        $responseDossierAgent = $dossierAgentWebService->getPersonalData($_ENV['SIHAM_WS_MATRICULE_TEST']);
        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseDossierAgent);
    }

    public function testGetAdministrativeData(){

        $dossierAgentWebService = new DossierAgentWebService();
        $responseDossierAgent = $dossierAgentWebService->getAdministrativeData($_ENV['SIHAM_WS_MATRICULE_TEST']);
        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseDossierAgent);
    }

    public function testSetPersonalData(){

        $dossierAgentWebService = new DossierAgentWebService();

        $responseDossierAgent = $dossierAgentWebService->addPhonePro($_ENV['SIHAM_WS_MATRICULE_TEST'], '0102030405');
        $this->assertEquals(true, $responseDossierAgent);

        $responseDossierAgent = $dossierAgentWebService->updatePhonePro($_ENV['SIHAM_WS_MATRICULE_TEST'], '0102030400');
        $this->assertEquals(true, $responseDossierAgent);

        $responseDossierAgent = $dossierAgentWebService->removePhonePro($_ENV['SIHAM_WS_MATRICULE_TEST']);
        $this->assertEquals(true, $responseDossierAgent);
    }
    

 }