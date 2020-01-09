<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Util\DossierParametrageWebService;
use App\Entity\Structure;


class SyncStructureCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:structure';
    private $em;
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
    }   

    protected function configure()
    {
        $this
         // configure an argument
         ->addArgument('logger_mode', InputArgument::OPTIONAL, 'The logger mode: console or logger.')
        // the short description shown while running "php bin/console list"
        ->setDescription('Sync all structures from SIHAM...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to import structures from SIHAM...')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = time();
        ini_set('default_socket_timeout', 300);

        $loggerMode = $input->getArgument('logger_mode');

        if ($loggerMode === 'logger') {
            $this->logger->info('Start sync structures');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Call DossierParametrageWebService... ');
        }
        $dossierParametrageWS = new DossierParametrageWebService();
        $structures = $dossierParametrageWS->getStructures();
        if (isset($structures->return)) {

            $numberOfStructures = count($structures->return);
            if ($loggerMode === 'logger') {
                $this->logger->info($numberOfStructures . ' structures found');
            } else {
                $io->writeln(\sprintf('<info>%s</info> structures found', $numberOfStructures));
                // creates a new progress bar
                $progressBar = new ProgressBar($io, $numberOfStructures);
                // starts and displays the progress bar
                $progressBar->start();
            }
            
            foreach($structures->return as $structureReturn) {
    
                // retrieve structure
                if ($loggerMode === 'logger') {
                    $this->logger->info('Get structure ' . $structureReturn->codeUO . ' from database');
                }
                $structure = $this->em->getRepository(Structure::class)->findOneByCodeUO($structureReturn->codeUO);
                if (!$structure) {
                    $structure = new Structure();
                }
                $structure->addStructureReturnFields($structureReturn);


                $this->em->persist($structure);
                $this->em->flush();
    
                if ($loggerMode !== 'logger') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

            }

            if ($loggerMode === 'logger') {
                $this->logger->info('Done in ' . (time() - $start) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();
                
                $io->success('Sync the structures from SIHAM was successfully done.');
            }

        } else {
            if ($loggerMode === 'logger') {
                $this->logger->error('No response from WebService');
            } else {
                $io->error('No response from WebService');
            }
        }
        
        
        return 0;
    }
}