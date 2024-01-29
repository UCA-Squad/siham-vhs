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
use App\Entity\Agent;


class SyncInpCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:inp';
    private $em;
    private $logger;
    private $project_dir;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, $project_dir) {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
        $this->project_dir = $project_dir;
    }

    protected function configure() {
        $this
        // configure an argument
        ->addOption('logger', null, InputOption::VALUE_OPTIONAL, 'The logger mode: "console" (by default) or "file".', 'console')

        // the short description shown while running "php bin/console list"
        ->setDescription('Sync users from INP...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to import users from INP...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $start = time();
        ini_set('default_socket_timeout', 300);

        $loggerMode = $input->getOption('logger');

        if ($loggerMode === 'file') {
            $this->logger->info('Start sync INP...');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Start sync INP... ');
        }

        // Read extract file from INP
        $rows = @file($this->project_dir . '/var/ext/' . $_ENV['INP_USERS_FILE']);
        if ($rows !== false && ($numberOfUsers = count($rows) - 1) > 0) { // headers on file

            if ($loggerMode === 'file') {
                $this->logger->info($numberOfUsers . ' users found');
            } else {
                $io->writeln(\sprintf('<info>%s</info> users found', $numberOfUsers));
                // creates a new progress bar (50 units)
                $progressBar = new ProgressBar($io, $numberOfUsers);
                // starts and displays the progress bar
                $progressBar->start();
            }

            $allowedPopulationTypes = ['employee', 'affiliate', 'faculty', 'researcher'];
            $matchCodeUOAffectations = [
                'SIGMA_CLERMONT' => 'UIU000000C',
                'INP_CLERMONT' => 'UI0000000I',
                'POLYTECH' => 'UIR000000C',
                'ISIMA' => 'UIQ000000C',
            ];
            $matchCivilite = ['M' => 1, 'MME' => 2];
            $fields = ['IdAurion','NomPatronymique','NomUsuel','Prenom','DateDeNaissance','Civilite','TelephoneProLong','TelephonePersonnel','MailPersonnel','LoginSigma','MailSigma','BadgeSigma','NumeroPorteMonnaieIZLY','DateDebut','DateFin','TypePopulation','CodeFormation','EntiteAffectation','SupannCodeINE'];
            foreach($rows as $row) {
                $detailAgent = array_combine($fields, explode(';', $row));

                if (\in_array($detailAgent['TypePopulation'], $allowedPopulationTypes)) {

                    // retrieve agent
                    $agent = $this->em->getRepository(Agent::class)->findOneByAurion($detailAgent['IdAurion']);
                    if ($loggerMode === 'file') {
                        $this->logger->info('Get agent with aurion ' . $detailAgent['IdAurion'] . ' from VHS database: ', ['found' => ($agent ? 'yes => UPDATE' : 'no => CREATE')]);
                    }
                    if (!$agent) {
                        $agent = new Agent();
                        $agent->setMatricule('INP' . str_pad($detailAgent['IdAurion'], 9, 0, STR_PAD_LEFT));
                        $agent->setAurion($detailAgent['IdAurion']);
                    }
                    if ($agent->getCodePositionStatutaire() != 'AC') {
                        $agent->setCodePositionStatutaire('AC');
                        $updatedFields['position statutaire'] = 'AC';
                    }

                    // For each attribute set if different and call webservice to write
                    $updatedFields = [];
                    $nomPatronymique = \strtoupper(trim($detailAgent['NomPatronymique']));
                    if (trim($agent->getNomPatronymique()) != $nomPatronymique) {
                        $agent->setNomPatronymique($nomPatronymique);
                        $updatedFields['nom patronymique'] = $nomPatronymique;
                    }
                    if (trim($agent->getNomUsuel()) != $nomPatronymique) {
                        $agent->setNomUsuel($nomPatronymique);
                        $updatedFields['nom usuel'] = $nomPatronymique;
                    }
                    $prenom = \strtoupper(trim($detailAgent['Prenom']));
                    if (trim($agent->getPrenom()) != $prenom) {
                        $agent->setPrenom($prenom);
                        $updatedFields['prenom'] = $prenom;
                    }
                    if (is_null($agent->getDateNaissance()) || trim($agent->getDateNaissance()->format('d/m/Y')) != trim($detailAgent['DateDeNaissance'])) {
                        $date = \DateTime::createFromFormat('d/m/Y', $detailAgent['DateDeNaissance']);
                        // if ($date !== false) {
                            $agent->setDateNaissance($date !== false ? $date : null);
                        // }
                        $updatedFields['date naissance'] = $date;
                    }
                    if (\array_key_exists($detailAgent['Civilite'], $matchCivilite) && $agent->getCivilite() != $matchCivilite[$detailAgent['Civilite']]) {
                        $civility = $detailAgent['Civilite'] == 'MME' ? 2 : 1;
                        $agent->setCivilite($civility);
                        $updatedFields['civilite'] = $detailAgent['Civilite'];
                    }
                    if (trim($agent->getUsername()) != trim($detailAgent['LoginSigma'])) {
                        $agent->setUsername($detailAgent['LoginSigma']);
                        $updatedFields['username'] = $detailAgent['LoginSigma'];
                    }
                    if (trim($agent->getMailPro()) != trim($detailAgent['MailSigma'])) {
                        $agent->setMailPro($detailAgent['MailSigma']);
                        $updatedFields['mail pro'] = $detailAgent['MailSigma'];
                    }

                    if ($agent->getQuotiteAffectationsHIE() != '100') {
                        $agent->setQuotiteAffectationsHIE('100');
                        $updatedFields['quotite HIE'] = '100';
                    }
                    if ($agent->getQuotiteAffectationsFUN() != '100') {
                        $agent->setQuotiteAffectationsFUN('100');
                        $updatedFields['quotite FUN'] = '100';
                    }
                    if (is_null($agent->getDateDebutAffectationsHIE()) || trim($agent->getDateDebutAffectationsHIE()->format('d/m/Y')) != trim($detailAgent['DateDebut'])) {
                        $date = \DateTime::createFromFormat('d/m/Y', $detailAgent['DateDebut']);
                        if ($date !== false) {
                            $agent->setDateDebutAffectationsHIE($date);
                        }
                        $updatedFields['date debut HIE'] = $date;
                    }
                    if (is_null($agent->getDateFinAffectationsHIE()) || trim($agent->getDateFinAffectationsHIE()->format('d/m/Y')) != trim($detailAgent['DateFin'])) {
                        $date = \DateTime::createFromFormat('d/m/Y', $detailAgent['DateFin']);
                        if ($date !== false) {
                            $agent->setDateFinAffectationsHIE($date);
                        }
                        $updatedFields['date fin HIE'] = $date;
                    }

                    if (\array_key_exists($detailAgent['EntiteAffectation'], $matchCodeUOAffectations) && $agent->getCodeUOAffectationsHIE() != $matchCodeUOAffectations[$detailAgent['EntiteAffectation']]) {
                        $affectation = isset($matchCodeUOAffectations[$detailAgent['EntiteAffectation']]) ? $matchCodeUOAffectations[$detailAgent['EntiteAffectation']] : $matchCodeUOAffectations['SIGMA_CLERMONT'];
                        $agent->setCodeUOAffectationsHIE($affectation);
                        $agent->setCodeUOAffectationsFUN($affectation);
                        $updatedFields['affectation'] = $affectation;
                    }

                    if ($loggerMode === 'file' && !empty($updatedFields))
                        $this->logger->info('- Update ' . $detailAgent['IdAurion'], $updatedFields);


                    $this->em->persist($agent);
                    $this->em->flush();

                    if ($loggerMode !== 'file') {
                        // advances the progress bar 1 unit
                        $progressBar->advance();
                    }

                    $numberOfUsers++;
                }
            }

            if ($loggerMode === 'file') {
                $this->logger->info('Done in ' . (time() - $start) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();

                $io->success('Sync the users from INP was successfully done.');
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

}