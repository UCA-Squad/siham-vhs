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


class SyncLdapCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:ldap';
    private $em;
    private $logger;
    private $project_dir;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, $project_dir)
    {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
        $this->project_dir = $project_dir;
    }   

    protected function configure()
    {
        $this
         // configure an argument
         ->addArgument('logger_mode', InputArgument::OPTIONAL, 'The logger mode: console or logger.')
        // the short description shown while running "php bin/console list"
        ->setDescription('Sync work phone and email of the users into SIHAM...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to export users attributes to SIHAM...')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = time();
        ini_set('default_socket_timeout', 300);

        $loggerMode = $input->getArgument('logger_mode');

        if ($loggerMode === 'logger') {
            $this->logger->info('Start sync ldap');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Read extract file... ');
        }

        // Read extract file from LDAP
        $rows = @file($this->project_dir . '/var/ldap/' . $_ENV['SIHAM_WS_LDAP_EXTRACT']);
        if ($rows !== false && ($numberOfUsers = count($rows)) > 0) {
            
            if ($loggerMode === 'logger') {
                $this->logger->info($numberOfUsers . ' agents found');
            } else {
                $io->writeln(\sprintf('<info>%s</info> users found', $numberOfUsers));
                // creates a new progress bar (50 units)
                $progressBar = new ProgressBar($io, $numberOfUsers);
                // starts and displays the progress bar
                $progressBar->start();
            }

            $dossierAgentWS = new DossierAgentWebService();

            $fields = ['matricule', 'telephonePro', 'uselessPhone', 'uselessFax', 'mailPro', 'username', 'status', 'badge', 'mailPerso', 'uselessEndLine'];
            // while (($row = fgetcsv($fi, 0, ';')) !== false) {
            foreach($rows as $row) {
                $detailAgent = array_combine($fields, explode(';', $row));

                // retrieve agent
                if ($loggerMode === 'logger') {
                    $this->logger->info('Get agent ' . $detailAgent['matricule'] . ' from database');
                }
                // $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($detailAgent['matricule']);
                $agent = $this->em->getRepository(Agent::class)->findOneByNumDossierHarpege($detailAgent['matricule']);
                if (!$agent) {
                    continue;
                }
                $detailAgent['matricule'] = $agent->getMatricule();// because call bu numDOssierHarpege
                // For each attribute set if different and call webservice to write
                if ($agent->getBadge() != $detailAgent['badge']) $agent->setBadge($detailAgent['badge']);
                
                if ($agent->getUsername() != $detailAgent['username']) {
                    $agent->setUsername($detailAgent['username']);

                    if ($loggerMode === 'logger') $this->logger->info('-- Write username for ' . $detailAgent['matricule']);
                    // $personalData = $dossierAgentWS->setPersonalData($detailAgent['matricule']);
                }
                if ($agent->getTelephonePro() != $detailAgent['telephonePro']) {
                    $agent->setTelephonePro($detailAgent['telephonePro']);

                    if ($loggerMode === 'logger') $this->logger->info('-- Write phone pro for ' . $detailAgent['matricule']);

                    if (empty($detailAgent['telephonePro']))    $personalData = $dossierAgentWS->removePhonePro($detailAgent['matricule']);
                    else if (empty($agent->getTelephonePro()))  $personalData = $dossierAgentWS->addPhonePro($detailAgent['matricule'], $detailAgent['telephonePro']);
                    else                                        $personalData = $dossierAgentWS->updatePhonePro($detailAgent['matricule'], $detailAgent['telephonePro']);
                }
                if ($agent->getMailPro() != $detailAgent['mailPro']) {
                    $agent->setMailPro($detailAgent['mailPro']);

                    if ($loggerMode === 'logger') $this->logger->info('-- Write email pro for ' . $detailAgent['matricule']);
                    
                    if (empty($detailAgent['mailPro']))     $personalData = $dossierAgentWS->removeEmailPro($detailAgent['matricule']);
                    else if (empty($agent->getMailPro()))   $personalData = $dossierAgentWS->addEmailPro($detailAgent['matricule'], $detailAgent['mailPro']);
                    else                                    $personalData = $dossierAgentWS->updateEmailPro($detailAgent['matricule'], $detailAgent['mailPro']);
                }
                if ($agent->getMailPerso() != $detailAgent['mailPerso']) {
                    $agent->setMailPerso($detailAgent['mailPerso']);

                    if ($loggerMode === 'logger') $this->logger->info('-- Write email perso for ' . $detailAgent['matricule']);

                    if (empty($detailAgent['mailPerso']))   $personalData = $dossierAgentWS->removeEmailPerso($detailAgent['matricule']);
                    else if (empty($agent->getMailPerso())) $personalData = $dossierAgentWS->addEmailPerso($detailAgent['matricule'], $detailAgent['mailPerso']);
                    else                                    $personalData = $dossierAgentWS->updateEmailPerso($detailAgent['matricule'], $detailAgent['mailPerso']);
                }


                $this->em->persist($agent);
                $this->em->flush();
    
                if ($loggerMode !== 'logger') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                $numberOfUsers++;
            }

            if ($loggerMode === 'logger') {
                $this->logger->info('Done in ' . (time() - $start) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();
                
                $io->success('Sync the users to SIHAM was successfully done.');
            }

        } else {
            if ($loggerMode === 'logger') {
                $this->logger->error('Empty or no file');
            } else {
                $io->error('Empty or no file');
            }
        }                      
        
        return 0;
    }
}