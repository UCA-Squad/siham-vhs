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

    public function testRestartWebservice() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $_ENV['SIHAM_WS_URL'] . '/manager/text/reload?path=/DossierParametrageWebService');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) $_ENV['SIHAM_WS_RESTART_DURATION']);
        $headers = ['Authorization: Basic '. base64_encode($_ENV['SIHAM_TOMCAT_USERNAME'] . ':' . $_ENV['SIHAM_TOMCAT_PASSWORD'])];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($ch);
        curl_close($ch);

        $this->assertEquals(true, strripos($result, 'OK') !== false);
    }
}