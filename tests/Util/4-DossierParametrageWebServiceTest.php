<?php

namespace App\Tests\Util;

use App\Util\DossierParametrageWebService;
use PHPUnit\Framework\TestCase;

class DossierParametrageWebServiceTest extends TestCase
{

    public function testGetStructures(){

        $dossierParametrageWebService = new DossierParametrageWebService();
        $responseDossierParametrage = $dossierParametrageWebService->getStructures(date('Y-m-d'), $_ENV['SIHAM_WS_CODEUO_TEST']);

        // There is more than one return
        $this->assertObjectHasAttribute('return', $responseDossierParametrage);
    }

    /**
     * @TODO
     * - ? list of attributes to fill database
     * 
     */
}