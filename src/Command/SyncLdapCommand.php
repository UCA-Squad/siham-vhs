<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Util\ListeAgentsWebService;
use App\Util\DossierAgentWebService;
use App\Entity\Agent;


class SyncLdapCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:ldap';
    private $em;
    private $logger;
    private $project_dir;
    private $dossierAgentWS;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, $project_dir) {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
        $this->project_dir = $project_dir;
        $this->dossierAgentWS = new DossierAgentWebService();
    }   

    protected function configure() {
        $this
        // configure an argument
        ->addOption('logger', null, InputOption::VALUE_OPTIONAL, 'The logger mode: "console" (by default) or "file".', 'console')

        // the short description shown while running "php bin/console list"
        ->setDescription('Sync work phone and email of the users into SIHAM...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to export users attributes to SIHAM...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $start = time();
        ini_set('default_socket_timeout', 300);

        $loggerMode = $input->getOption('logger');

        if ($loggerMode === 'file') {
            $this->logger->info('Start sync ldap...');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Start sync ldap... ');
        }

        // Read extract file from LDAP
        $rows = @file($this->project_dir . '/var/ldap/' . $_ENV['SIHAM_WS_LDAP_EXTRACT']);
        if ($rows !== false && ($numberOfUsers = count($rows)) > 0) {
            
            if ($loggerMode === 'file') {
                $this->logger->info($numberOfUsers . ' agents found');
            } else {
                $io->writeln(\sprintf('<info>%s</info> users found', $numberOfUsers));
                // creates a new progress bar (50 units)
                $progressBar = new ProgressBar($io, $numberOfUsers);
                // starts and displays the progress bar
                $progressBar->start();
            }

            $fields = ['matricule', 'telephonePro', 'uselessPhone', 'mailPro', 'username', 'status', 'badge', 'mailPerso', 'uselessEndLine'];
            foreach($rows as $row) {
                $detailAgent = array_combine($fields, explode(';', $row));

                // retrieve agent
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($detailAgent['matricule']);
                if ($loggerMode === 'file') {
                    $this->logger->info('Get agent ' . $detailAgent['matricule'] . ' from VHS database: ', ['found' => ($agent ? 'yes => CONTINUE' : 'no => BREAK')]);
                }
                if (!$agent) {
                    continue;
                }
                
                // For each attribute set if different and call webservice to write
                if (trim($agent->getBadge()) != trim($detailAgent['badge'])) {
                    $agent->setBadge($detailAgent['badge']);

                    if ($loggerMode === 'file') $this->logger->info('- Update badge', ['badge' => $detailAgent['badge']]);
                }
                if (trim($agent->getUsername()) != trim($detailAgent['username'])) {
                    $agent->setUsername($detailAgent['username']);

                    if ($loggerMode === 'file') $this->logger->info('- Update username', ['username' => $detailAgent['username']]);
                }
                
                // for TPR, MPR, MPE with WS in need 
                $this->setPersonalDataWithWS($agent, $detailAgent, $loggerMode);


                $this->em->persist($agent);
                $this->em->flush();
    
                if ($loggerMode !== 'file') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                $numberOfUsers++;
            }

            if ($loggerMode === 'file') {
                $this->logger->info('Done in ' . (time() - $start) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();
                
                $io->success('Sync the users to SIHAM was successfully done.');
            }

        } else {
            if ($loggerMode === 'file') {
                $this->logger->error('Empty or no file');
            } else {
                $io->error('Empty or no file');
            }
        }                      
        
        return 0;
    }

    public function setPersonalDataWithWS($agent, $detailAgent, $loggerMode) {
        $types = [
            'telephonePro'  => 'phonePro',
            'mailPro'       => 'emailPro',
            'mailPerso'     => 'emailPerso'
        ];

        foreach ($types as $typeName => $typeMethod) {
            
            $getter = 'get' . \ucfirst($typeName);
            $setter = 'set' . \ucfirst($typeName);
            $newAction = false;

            if (trim($agent->$getter()) != trim($detailAgent[$typeName])) { // trim to include null and empty values
                    
                $action = empty($detailAgent[$typeName]) ? 'remove' : (empty($agent->$getter()) ? 'add' : 'update');
                if ($loggerMode === 'file') $this->logger->info('- ' . \ucfirst($action) . ' (VHS and SIHAM) ' . $typeName,  [$typeMethod => $detailAgent[$typeName]]);
                
                $actionMethod = $action . \ucfirst($typeMethod);
                $personalData = $this->dossierAgentWS->$actionMethod($detailAgent['matricule'], $detailAgent[$typeName]);
                if ($personalData == 'TYPTEL_NON_TROUVEE' || $personalData == 'TYPTEL_DEJA_PRESENT') {
                    $newAction = $action == 'add' ? 'update' : ($action == 'update' ? 'add' : 'ignore');
                    $this->logger->warning('Cannot ' . $action . ' by WS so ' . $newAction .' it',  ['ws' => 'DossierAgentWebService', 'method' => 'modifDonneesPersonnelles','cause' => 'no response OR failed ' . $action]);
                    $newActionMethod = $newAction . \ucfirst($typeMethod);
                    if (\method_exists($agent, $newActionMethod)) {
                        $personalData = $this->dossierAgentWS->$newActionMethod($detailAgent['matricule'], $detailAgent[$typeName]);
                    } else {
                        $personalData = 1;
                    }
                }
                
                if ($personalData == 1) {
                    $agent->$setter($detailAgent[$typeName]);
                    $this->logger->info('- Done ' . ($newAction ?: $action) . ' by WS');
                } else if ($loggerMode === 'file') {
                    $this->logger->warning('Cannot be saved by WS',  ['ws' => 'DossierAgentWebService', 'method' => 'modifDonneesPersonnelles','cause' => 'no response OR failed ' . ($newAction ?: $action)]);
                }
            }
        }
    }
}