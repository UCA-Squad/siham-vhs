<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;

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
     * @Route("/sync/{fileName}", name="sync_result")
     */
    public function syncResult($fileName = null)
    {
        $title = 'No sync result yet';

        $files = [];
        $fileToDisplay = null;
        $finder = new Finder();
        $finder->files()->in(dirname(__DIR__) . '/../templates/sync/')
                        ->name($_ENV['APP_ENV'] . '.sync.*.log')
                        ->sortByName()->reverseSorting();
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                // Set file from finder, we cannot simply use finder to twig 
                preg_match('/' . $_ENV['APP_ENV'] . '.sync.(.*?)\-/s', $file->getPathname(), $matches);
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

        return $this->render('sync/sync_result.html.twig', [
            'title' => $title,
            'fileToDisplay' => $fileToDisplay,
            'files' => $files
        ]);
    }

}