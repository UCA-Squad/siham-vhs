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
        $title = 'No result tests yet';

        $testsResultFile = dirname(__DIR__) . '/../templates/tests/test.all.html';
        if (file_exists($testsResultFile)) {
            $title =  'Result tests at ' . date ('D jS Y H:i:s', filemtime($testsResultFile));
        }

        return $this->render('tests/result.html.twig', [
            'title' => $title
        ]);
    }
}