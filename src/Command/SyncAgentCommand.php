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
        $startObservationDate = new \DateTime($fromDate!= 'all' ? $fromDate : null);
        $endObservationDate = new \DateTime($fromDate!= 'all' ? $fromDate : null);
        $endObservationDate->modify('+60 days'); // to include the future contracts

        if ($loggerMode === 'file') {
            $this->logger->info('Start sync agents');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Call ListeAgentsWebService... ');
        }

        $listAgentsWS = new ListeAgentsWebService();

        // Call SIHAM WS according to option (all or only updated agents)
        $listAgents = NULL;
        if ($fromDate === 'all') {
            $listAgents = $listAgentsWS->getListAgentsByName('%');
            if ($loggerMode === 'file') {
                $this->logger->info('with all agents');
            } else {
                $io->write(' with recupListAgents... ');
            }
        } else {
            $listAgents = $listAgentsWS->getListAgentsUpdated($startObservationDate->format('Y-m-d'));
            if ($loggerMode === 'file') {
                $this->logger->info('with updated agents at ' . $fromDate);
            } else {
                $io->write(' with updated agents at ' . $fromDate . '... ');
            }
        }
        
        // Warning or bad response from SIHAM webservices
        if (!isset($listAgents->return)) {
            if ($loggerMode === 'file') {
                $this->logger->error('No response from WebService');
            } else {
                $io->error('No response from WebService');
            }
            return 0;
        }

        // Keep matricules, no more need other data
        $listAgents = is_array($listAgents->return) ? $listAgents->return : [$listAgents->return];
        $listAgents = \array_column($listAgents, 'matricule');

        // Call SIHAM WS to retrieve due term agents
        $dueTermAgents = $listAgentsWS->getListAgentsDueTerm($startObservationDate->format('Y-m-d'), $endObservationDate->format('Y-m-d'));
        if (isset($dueTermAgents->return)) {
            // And add them to the previous list
            $dueTermAgents = is_array($dueTermAgents->return) ? $dueTermAgents->return : [$dueTermAgents->return];
            $listAgents = \array_merge($listAgents, \array_column($dueTermAgents, 'matricule'));
            $listAgents = \array_unique($listAgents);
        }


        if (!empty($listAgents)) {
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
            
            // $counterTempo = 1;
            foreach($listAgents as $agentSihamId) {
                // retrieve agent
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($agentSihamId);
                if ($loggerMode === 'file') {
                    $this->logger->info('Get agent ' . $agentSihamId . ' from database: ' . ($agent ? 'found' : 'not found'));
                }
                // Or create
                if (!$agent) {
                    $agent = new Agent();
                    $agent->setMatricule($agentSihamId);
                }


                $dossierAgentWS = new DossierAgentWebService();

                // Call SIHAM WS to get personal data
                if ($loggerMode === 'file') {
                    $this->logger->info('-- Get personal data for ' . $agentSihamId);
                }
                $personalData = $dossierAgentWS->getPersonalData($agentSihamId, $startObservationDate->format('Y-m-d'), $endObservationDate->format('Y-m-d'));
                if (isset($personalData->return))
                    $agent->addPersonalData($personalData->return);


                // Call SIHAM WS to get administrative data
                if ($loggerMode === 'file') {
                    $this->logger->info('-- Get administrative data for ' . $agentSihamId);
                }
                $administrativeData = $dossierAgentWS->getAdministrativeData($agentSihamId, $startObservationDate->format('Y-m-d'), $endObservationDate->format('Y-m-d'));
                if (isset($administrativeData->return))
                    $agent->addAdministrativeData($administrativeData->return);


                // Save it
                $this->em->persist($agent);
                $this->em->flush();
    
                if ($loggerMode !== 'file') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                // take a break for webservice :-( 
                // Temporally disable since VM up memory to 6Go --> 14go
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