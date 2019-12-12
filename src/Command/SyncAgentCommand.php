<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Doctrine\ORM\EntityManagerInterface;
use App\Util\ListeAgentsWebService;
use App\Util\DossierAgentWebService;
use App\Entity\Agent;


class SyncAgentCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:agent';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }   

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Sync all users from SIHAM...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to import users from SIHAM...')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // $io->title('Sync Users');

        $io->newLine();
        $io->write('Call ListeAgentsWebService... ');
        $listAgentsWS = new ListeAgentsWebService();
        $listAgents = $listAgentsWS->getListAgentsByName('MARTIN');
        if (isset($listAgents->return)) {

            $numberOfUsers = count($listAgents->return);
            $io->writeln(\sprintf('<info>%s</info> users found', $numberOfUsers));
    
            // creates a new progress bar (50 units)
            $progressBar = new ProgressBar($io, $numberOfUsers);
    
            // starts and displays the progress bar
            $progressBar->start();
            foreach($listAgents->return as $listAgent) {
    
                // retrieve agent
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($listAgent->matricule);
                // dump($agent);
                if (!$agent) {
                    $agent = new Agent();
                }
                $agent->addListAgentFields($listAgent);


                $dossierAgentWS = new DossierAgentWebService();

                $personalData = $dossierAgentWS->getPersonalData($listAgent->matricule);
                if (isset($personalData->return))
                    $agent->addPersonalData($personalData->return);


                $administrativeData = $dossierAgentWS->getAdministrativeData($listAgent->matricule);
                if (isset($administrativeData->return))
                    $agent->addAdministrativeData($administrativeData->return);

                $this->em->persist($agent);
    
                // advances the progress bar 1 unit
                $progressBar->advance();
                // \usleep(1 * 100000);

                
                // if (!isset($true) or !isset($second)) {
                //     $io->warning('Empty values for uid: ' . $listAgent->matricule);
                //     if (isset($true))
                //         $second = true;
                //     $true = true;
                    
                // }
            }
            $this->em->flush();

            // ensures that the progress bar is at 100%
            $progressBar->finish();
            
            $io->success('Sync the users from SIHAM was successfully done.');
            return 0;

        }
        
        $io->error('No response from WebService');
        
        return 0;
    }
}