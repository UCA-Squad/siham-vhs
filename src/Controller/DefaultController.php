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
            $title =  'Tests result at ' . date ('D jS M Y H:i:s', filemtime($testsResultFile));
        }

        return $this->render('tests/result.html.twig', [
            'title' => $title
        ]);
    }

    /**
     * @Route("/sync/structure", name="sync_structure_result")
     */
    public function syncStructureResult()
    {
        $title = 'No sync structure result yet';

        $syncResultFile = dirname(__DIR__) . '/../templates/sync/' . $_ENV['APP_ENV'] . '.sync.structure.log';
        if (file_exists($syncResultFile)) {
            $title =  'Sync structure result at ' . date ('D jS M Y H:i:s', filemtime($syncResultFile));
        }

        return $this->render('sync/sync_structure_result.html.twig', [
            'title' => $title
        ]);
    }

    /**
     * @Route("/sync/agent", name="sync_agent_result")
     */
    public function syncAgentResult()
    {
        $title = 'No sync agent result yet';

        $syncResultFile = dirname(__DIR__) . '/../templates/sync/' . $_ENV['APP_ENV'] . '.sync.agent.log';
        if (file_exists($syncResultFile)) {
            $title =  'Sync agent result at ' . date ('D jS M Y H:i:s', filemtime($syncResultFile));
        }

        return $this->render('sync/sync_agent_result.html.twig', [
            'title' => $title
        ]);
    }

    /**
     * @Route("/sync/ldap", name="sync_ldap_result")
     */
    public function syncLdapResult()
    {
        $title = 'No sync ldap result yet';

        $syncResultFile = dirname(__DIR__) . '/../templates/sync/' . $_ENV['APP_ENV'] . '.sync.ldap.log';
        if (file_exists($syncResultFile)) {
            $title =  'Sync ldap result at ' . date ('D jS M Y H:i:s', filemtime($syncResultFile));
        }

        return $this->render('sync/sync_ldap_result.html.twig', [
            'title' => $title
        ]);
    }
}