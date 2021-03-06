<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() {
        return $this->redirectToRoute('test_result', ['env' => $_ENV['APP_ENV']]);
    }

    /**
     * @Route("/test/{env}", name="test_result")
     */
    public function testResult($env) {

        if(!isset($env)) $env = $_ENV['APP_ENV'];

        $title = 'No tests result yet';
        $testsResultFile = dirname(__DIR__) . '/../templates/test/' . $env . '.phpunit.html';
        if (file_exists($testsResultFile)) {
            $title =  'Tests result at ' . date ('D jS M Y H:i:s', filemtime($testsResultFile));
        }

        return $this->render('test/result.html.twig', [
            'title' => $title,
            'env' => $env
        ]);
    }

    /**
     * @Route("/sync/{env}/{fileName}", name="sync_result")
     */
    public function syncResult($env, $fileName = null) {

        if(!isset($env)) $env = $_ENV['APP_ENV'];

        $title = 'No sync result yet';

        $files = [];
        $fileToDisplay = null;
        $finder = new Finder();
        $finder->files()->in(dirname(__DIR__) . '/../templates/sync/')
                        ->name($env . '.sync.*.log')
                        ->sortByName()->reverseSorting();
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                // Set file from finder, we cannot simply use finder to twig 
                preg_match('/' . $env . '.sync.(.*?)\-/s', $file->getPathname(), $matches);
                if (!isset($logType) || $matches[1] != $logType)
                    $logType = $matches[1];
                $files[$matches[1]][] = $file;
                // Distinct the file to display by the selected or default
                if (empty($fileToDisplay) && (is_null($fileName) || $fileName == $file->getRelativePathname())) {
                    $fileToDisplay = $file;
                    $title =  'Sync ' . $logType . ' result at ' . date ('D jS M Y H:i:s', $fileToDisplay->getMTime());
                }
            }
        }

        return $this->render('sync/result.html.twig', [
            'title' => $title,
            'fileToDisplay' => $fileToDisplay,
            'files' => $files,
            'env' => $env
        ]);
    }

}