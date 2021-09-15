<?php

namespace App\Tests\Util;

use App\Util\DossierAgentWebService;
use PHPUnit\Framework\TestCase;

class DossierAgentWebServiceTest extends TestCase
{

    public function testGetPersonalData(){

        $dossierAgentWebService = new DossierAgentWebService();

        $responseDossierAgentGetPD = $dossierAgentWebService->getPersonalData($_ENV['SIHAM_WS_MATRICULE_TEST']);
        $this->assertObjectHasAttribute('return', $responseDossierAgentGetPD);

        $responseDossierAgentGetPDBySD = $dossierAgentWebService->getPersonalData($_ENV['SIHAM_WS_MATRICULE_TEST'], date('Y-m-d'));
        $this->assertObjectHasAttribute('return', $responseDossierAgentGetPDBySD);

        $responseDossierAgentGetPDBySDAndED = $dossierAgentWebService->getPersonalData($_ENV['SIHAM_WS_MATRICULE_TEST'], date('Y-m-d'), date('Y-m-d', \strtotime('+30 days')));
        $this->assertObjectHasAttribute('return', $responseDossierAgentGetPDBySDAndED);
    }

    public function testGetAdministrativeData(){

        $dossierAgentWebService = new DossierAgentWebService();
        
        $responseDossierAgentGetAD = $dossierAgentWebService->getAdministrativeData($_ENV['SIHAM_WS_MATRICULE_TEST']);
        $this->assertObjectHasAttribute('return', $responseDossierAgentGetAD);

        $responseDossierAgentGetADBySD = $dossierAgentWebService->getAdministrativeData($_ENV['SIHAM_WS_MATRICULE_TEST'], date('Y-m-d'));
        $this->assertObjectHasAttribute('return', $responseDossierAgentGetADBySD);

        $responseDossierAgentGetADBySDAndED = $dossierAgentWebService->getAdministrativeData($_ENV['SIHAM_WS_MATRICULE_TEST'], date('Y-m-d'), date('Y-m-d', \strtotime('+30 days')));
        $this->assertObjectHasAttribute('return', $responseDossierAgentGetADBySDAndED);
    }

    public function testSetPersonalData(){

        $dossierAgentWebService = new DossierAgentWebService();

        $responseDossierAgentAddPD = $dossierAgentWebService->addPhoneProfessional($_ENV['SIHAM_WS_MATRICULE_TEST'], '0102030405');
        $this->assertEquals(1, $responseDossierAgentAddPD);

        $responseDossierAgentUpdatePD = $dossierAgentWebService->updatePhoneProfessional($_ENV['SIHAM_WS_MATRICULE_TEST'], '0102030400');
        $this->assertEquals(1, $responseDossierAgentUpdatePD);

        $responseDossierAgentRemovePD = $dossierAgentWebService->removePhoneProfessional($_ENV['SIHAM_WS_MATRICULE_TEST']);
        $this->assertEquals(1, $responseDossierAgentRemovePD);
    }
    
    public function testGetEchelon() {
        $dossierAgentWebService = new DossierAgentWebService();

        $responseDossierGetEchelon = $dossierAgentWebService->getEchelon($_ENV['SIHAM_WS_MATRICULE_TEST']);
        $this->assertObjectHasAttribute('return', $responseDossierGetEchelon);
    }

 }