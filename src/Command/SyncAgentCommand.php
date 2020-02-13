<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
// use Doctrine\ORM\EntityManagerInterface;
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
        $this->em = $doctrine->getManager();// siham_vhs by default //$em;
        $this->geishaEm = $doctrine->getManager('geisha');
        // $this->geishaEm = $this->getDoctrine()->getManager('geisha');
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
            $listAgents = is_array($listAgents->return) ? $listAgents->return : [$listAgents->return];
            $numberOfUsers = count($listAgents);
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
            foreach($listAgents as $listAgent) {
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

                // GEISHA
                $codeUOAffectationsAGR      = [];
                $dateDebutUOAffectationsAGR = [];
                $dateFinUOAffectationsAGR   = [];
                if (\strstr($agent->getCodeUOAffectationsFUN(), 'U0B000000L') !== false || $agent->getTemEnseignantChercheur() == 'O') {
                    if ($loggerMode === 'logger') {
                        $this->logger->info('-- Get geisha data for ' . $listAgent->matricule);
                    }
                    $conn = $this->geishaEm->getConnection();
                    $sql = 'SELECT C_STRUCTURE, TO_CHAR(D_DEB_VAL, \'YYYY-MM-DD\') AS D_DEB_VAL, TO_CHAR(D_FIN_VAL, \'YYYY-MM-DD\') AS D_FIN_VAL FROM AGREMENT WHERE NO_INDIVIDU = :numDossierHarpege AND ((D_DEB_VAL <= TO_DATE(:dateDebutObservation, \'YYYY-MM-DD\') AND D_FIN_VAL >= TO_DATE(:dateDebutObservation, \'YYYY-MM-DD\')) OR (D_DEB_VAL <= TO_DATE(:dateFinObservation, \'YYYY-MM-DD\') AND D_FIN_VAL >= TO_DATE(:dateFinObservation, \'YYYY-MM-DD\'))) ORDER BY D_DEB_VAL';
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue('numDossierHarpege', $agent->getNumDossierHarpege());
                    $dateObservation = new \DateTime();
                    $stmt->bindValue('dateDebutObservation', $dateObservation->format('Y-m-d'));
                    $stmt->bindValue('dateFinObservation', $dateObservation->modify('+60 days')->format('Y-m-d'));
                    $stmt->execute();
                    $agreements = $stmt->fetchAll();
                    if (!empty($agreements)) {
                        foreach ($agreements as $agreement) {
                            $codeUOAffectationsAGR[]        = $agreement['C_STRUCTURE'];
                            $dateDebutUOAffectationsAGR[]   = $agreement['D_DEB_VAL'];
                            $dateFinUOAffectationsAGR[]     = $agreement['D_FIN_VAL'];
                        }
                    }
                }
                $agent->setCodeUOAffectationsAGR(\implode('|', $codeUOAffectationsAGR));
                $agent->setDateDebutUOAffectationsAGR(\implode('|', $dateDebutUOAffectationsAGR));
                $agent->setDateFinUOAffectationsAGR(\implode('|', $dateFinUOAffectationsAGR));

                $this->em->persist($agent);
                $this->em->flush();
    
                if ($loggerMode !== 'logger') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                // take a break for webservice :-( 
                // Temporally disable since VM up memory to 6Go
                // if ($counterTempo++ % 250 == 0) \sleep(15);
                // if ($counterTempo++ % 1000 == 0) \sleep(30);
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