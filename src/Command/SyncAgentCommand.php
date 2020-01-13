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
use App\Util\ListeAgentsWebService;
use App\Util\DossierAgentWebService;
use App\Entity\Agent;


class SyncAgentCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:agent';
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
        ->setDescription('Sync all users from SIHAM...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to import users from SIHAM...')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = time();
        ini_set('default_socket_timeout', 300);

        $loggerMode = $input->getArgument('logger_mode');

        if ($loggerMode === 'logger') {
            $this->logger->info('Start sync agents');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Call ListeAgentsWebService... ');
        }
        $listAgentsWS = new ListeAgentsWebService();
        $listAgents = $listAgentsWS->getListAgentsByName('%');
        if (isset($listAgents->return)) {

            $numberOfUsers = count($listAgents->return);
            if ($loggerMode === 'logger') {
                $this->logger->info($numberOfUsers . ' agents found');
            } else {
                $io->writeln(\sprintf('<info>%s</info> agents found', $numberOfUsers));
                // creates a new progress bar
                $progressBar = new ProgressBar($io, $numberOfUsers);
                // starts and displays the progress bar
                $progressBar->start();
            }
            
            $counterTempo = 1;
            foreach($listAgents->return as $listAgent) {
    
                // retrieve agent
                if ($loggerMode === 'logger') {
                    $this->logger->info('Get agent ' . $listAgent->matricule . ' from database');
                }
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($listAgent->matricule);
                if (!$agent) {
                    $agent = new Agent();
                }
                $agent->addListAgentFields($listAgent);


                $dossierAgentWS = new DossierAgentWebService();

                if ($loggerMode === 'logger') {
                    $this->logger->info('-- Get personal data for ' . $listAgent->matricule);
                }
                $personalData = $dossierAgentWS->getPersonalData($listAgent->matricule);
                if (isset($personalData->return))
                    $agent->addPersonalData($personalData->return);


                if ($loggerMode === 'logger') {
                    $this->logger->info('-- Get administrative data for ' . $listAgent->matricule);
                }
                $administrativeData = $dossierAgentWS->getAdministrativeData($listAgent->matricule);
                if (isset($administrativeData->return))
                    $agent->addAdministrativeData($administrativeData->return);

                $this->em->persist($agent);
                $this->em->flush();
    
                if ($loggerMode !== 'logger') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                // take a break for webservice :-( 
                if ($counterTempo++ % 500 == 0) \sleep(30);
            }

            if ($loggerMode === 'logger') {
                $this->logger->info('Done in ' . (time() - $start) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();
                
                $io->success('Sync the users from SIHAM was successfully done in ' . (time() - $start) . 's');
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