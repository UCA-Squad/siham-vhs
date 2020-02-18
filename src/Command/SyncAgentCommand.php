<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use App\Util\ListeAgentsWebService;
use App\Util\DossierAgentWebService;
use App\Entity\Agent;


class SyncAgentCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:agent';
    private $em;
    private $geishaEm;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        parent::__construct();
        $this->em = $doctrine->getManager();// siham_vhs by default;
        $this->geishaEm = $doctrine->getManager('geisha');
        $this->logger = $logger;
    }   

    protected function configure()
    {
        $this
         // configure options
        ->addOption('logger', null, InputOption::VALUE_OPTIONAL, 'The logger mode: "console" (by default) or "file".', 'console')
        ->addOption('from-date', null, InputOption::VALUE_OPTIONAL, '"all" or date in "Y-m-d" format (date of the day by default)', date('Y-m-d'))

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
        ini_set('default_socket_timeout', 600);

        $loggerMode = $input->getOption('logger');
        $fromDate = $input->getOption('from-date');
        
        if ($loggerMode === 'file') {
            $this->logger->info('Start sync agents');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Call ListeAgentsWebService... ');
        }

        $listAgentsWS = new ListeAgentsWebService();
        $listAgents = NULL;
        if ($fromDate === 'all') {
            $listAgents = $listAgentsWS->getListAgentsByName('%');
        } else {
            $listAgents = $listAgentsWS->getListAgentsModifies($fromDate);
        }

        if (isset($listAgents->return)) {
            $listAgents = is_array($listAgents->return) ? $listAgents->return : [$listAgents->return];
            $numberOfUsers = count($listAgents);
            if ($loggerMode === 'file') {
                $this->logger->info($numberOfUsers . ' agents found');
            } else {
                $io->writeln(\sprintf('<info>%s</info> agents found', $numberOfUsers));
                // creates a new progress bar
                $progressBar = new ProgressBar($io, $numberOfUsers);
                // starts and displays the progress bar
                $progressBar->start();
            }
            
            $counterTempo = 1;
            foreach($listAgents as $listAgent) {
                // retrieve agent
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($listAgent->matricule);
                if ($loggerMode === 'file') {
                    $this->logger->info('Get agent ' . $listAgent->matricule . ' from database: ' . ($agent ? 'found' : 'not found'));
                }
                if (!$agent) {
                    $agent = new Agent();
                    $agent->setMatricule($listAgent->matricule);
                }


                $dossierAgentWS = new DossierAgentWebService();

                if ($loggerMode === 'file') {
                    $this->logger->info('-- Get personal data for ' . $listAgent->matricule);
                }
                $personalData = $dossierAgentWS->getPersonalData($listAgent->matricule);
                if (isset($personalData->return))
                    $agent->addPersonalData($personalData->return);


                if ($loggerMode === 'file') {
                    $this->logger->info('-- Get administrative data for ' . $listAgent->matricule);
                }
                $administrativeData = $dossierAgentWS->getAdministrativeData($listAgent->matricule);
                if (isset($administrativeData->return))
                    $agent->addAdministrativeData($administrativeData->return);


                $this->em->persist($agent);
                $this->em->flush();
    
                if ($loggerMode !== 'file') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                // take a break for webservice :-( 
                // Temporally disable since VM up memory to 6Go
                // if ($counterTempo++ % 250 == 0) \sleep(15);
                // if ($counterTempo++ % 1000 == 0) \sleep(30);
            }

            if ($loggerMode === 'file') {
                $this->logger->info('Done in ' . (time() - $start) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();
                
                $io->success('Sync the users from SIHAM was successfully done in ' . (time() - $start) . 's');
            }

        } else {
            if ($loggerMode === 'file') {
                $this->logger->error('No response from WebService');
            } else {
                $io->error('No response from WebService');
            }
        }
        
        
        return 0;
    }
}