<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="tests_result")
     */
    public function testsResult()
    {
        $title = 'No tests result yet';

        $testsResultFile = dirname(__DIR__) . '/../templates/tests/test.all.html';
        if (file_exists($testsResultFile)) {
            $title =  'Tests result at ' . date ('D jS Y H:i:s', filemtime($testsResultFile));
        }

        return $this->render('tests/result.html.twig', [
            'title' => $title
        ]);
    }

    /**
     * @Route("/sync", name="sync_result")
     */
    public function syncResult()
    {
        $title = 'No sync result yet';

        $syncResultFile = dirname(__DIR__) . '/../templates/sync/' . $_ENV['APP_ENV'] . '.sync.log';
        if (file_exists($syncResultFile)) {
            $title =  'Sync result at ' . date ('D jS Y H:i:s', filemtime($syncResultFile));
        }

        return $this->render('sync/result.html.twig', [
            'title' => $title
        ]);
    }

    /**
     * @Route("/ldap", name="ldap")
     */
    public function ldap() {
        return new Response("LOL",Response::HTTP_OK);
    }
}