<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use App\Util\ListeAgentsWebService;
use App\Util\DossierAgentWebService;
use App\Entity\Agent;
use App\Entity\Structure;


class SyncGeishaCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'sync:geisha';
    private $em;
    private $geishaEm;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        parent::__construct();
        $this->em = $doctrine->getManager();// siham_vhs by default
        $this->geishaEm = $doctrine->getManager('geisha');
        $this->logger = $logger;
    }   

    protected function configure()
    {
        $this
         // configure options
        ->addOption('logger', null, InputOption::VALUE_OPTIONAL, 'The logger mode: "console" (by default) or "file".', 'console')

        // the short description shown while running "php bin/console list"
        ->setDescription('Sync all users from GEISHA...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to retrieve agreements from GEISHA...')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = time();

        $loggerMode = $input->getOption('logger');
        
        if ($loggerMode === 'file') {
            $this->logger->info('Start sync agreements from GEISHA ...');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Start sync agreements from GEISHA... ');
        }

        $connGeisha = $this->geishaEm->getConnection();
        $sqlGeishaAgreements = 'SELECT I.MATCLE, A.C_STRUCTURE, TO_CHAR(A.D_DEB_VAL, \'YYYY-MM-DD\') AS D_DEB_VAL, TO_CHAR(A.D_FIN_VAL, \'YYYY-MM-DD\') AS D_FIN_VAL 
            FROM GEI_ADM.AGREMENT A
            LEFT JOIN GEI_ADM.INDIVIDU2 I ON I.NO_INDIVIDU = A.NO_INDIVIDU 
            WHERE A.D_DEB_VAL <= TO_DATE(:dateFinObservation, \'YYYY-MM-DD\') AND A.D_FIN_VAL >= TO_DATE(:dateDebutObservation, \'YYYY-MM-DD\')
            ORDER BY I.MATCLE, A.C_STRUCTURE, A.D_DEB_VAL';
        $stmtGeisha = $connGeisha->prepare($sqlGeishaAgreements);
        $dateObservation = new \DateTime();
        $stmtGeisha->bindValue('dateDebutObservation', $dateObservation->format('Y-m-d'));
        $stmtGeisha->bindValue('dateFinObservation', $dateObservation->modify('+' . (int) $_ENV['DAYS_OBSERVATION_END_DATE'] . '  days')->format('Y-m-d'));
        $stmtGeisha->execute();
        $agreements = $stmtGeisha->fetchAll();
        if (!empty($agreements)) {
            
            // Agreements exist or GEISHA response
            // REMOVE all AGR fields
            $sqlRemoveAgreements = 'UPDATE `agent` SET `codeUOAffectationsAGR` = NULL, `dateDebutAffectationsAGR` = NULL, `dateFinAffectationsAGR` = NULL';
            $conn = $this->em->getConnection();
            $stmt = $conn->prepare($sqlRemoveAgreements);
            $stmt->execute();
            $numberOfAgreementsUpdated = $stmt->rowCount();
            
            $numberOfAgreementsGeisha = count($agreements);
            if ($loggerMode === 'file') {
                $this->logger->info($numberOfAgreementsUpdated . ' agents with reset agreements', ['db' => 'VHS']);
                $this->logger->info($numberOfAgreementsGeisha . ' agreements found', ['db' => 'GEISHA']);
            } else {
                $io->writeln(\sprintf('(<info>%s</info> agent agreements reset) <info>%s</info> agreements found', $numberOfAgreementsUpdated, $numberOfAgreementsGeisha));
                // creates a new progress bar
                $progressBar = new ProgressBar($io, $numberOfAgreementsGeisha);
                // starts and displays the progress bar
                $progressBar->start();
            }


            // Loop on each agreement to set it to the agent
            foreach ($agreements as $agreement) {
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($agreement['MATCLE']);
                if ($agent) {
                    // Get previous UO and date of agreements to concatenate them
                    $codeUOAffectationsAGR      = explode('|', $agent->getCodeUOAffectationsAGR());
                    $dateDebutAffectationsAGR   = $agent->getDateDebutAffectationsAGR();
                    $dateFinAffectationsAGR     = $agent->getDateFinAffectationsAGR();
                    
                    $codeUOAffectationAGR = $agreement['C_STRUCTURE'];
                    $structure = $this->em->getRepository(Structure::class)->findOneInCodeHarpege($agreement['C_STRUCTURE']);
                    if ($structure) {
                        $codeUOAffectationAGR = $structure->getCodeUO();
                    }
                    if ($loggerMode === 'file') {
                        $this->logger->info('Set agreement ' . $agreement['C_STRUCTURE']  . '  for ' . $agent->getMatricule());
                        if ($agreement['C_STRUCTURE'] != $codeUOAffectationAGR)
                            $this->logger->info('- Set GEISHA agreement ' . $agreement['C_STRUCTURE'] . ' with correspond SIHAM code ' . $codeUOAffectationAGR);
                    }
                    // Add next agreement
                    $codeUOAffectationsAGR[]    = $codeUOAffectationAGR;
                    // Keep the smallest and the biggest date
                    $dateDebutAffectationsAGRCurrent = new \DateTime(\substr($agreement['D_DEB_VAL'],0,10));
                    if (empty($this->dateDebutAffectationsAGR) || $this->dateDebutAffectationsAGR > $dateDebutAffectationsAGRCurrent)
                        $dateDebutAffectationsAGR = $dateDebutAffectationsAGRCurrent;
                    $dateFinAffectationsAGRCurrent = new \DateTime(\substr($agreement['D_FIN_VAL'],0,10));
                    if (empty($this->dateFinAffectationsAGR) || $this->dateFinAffectationsAGR < $dateFinAffectationsAGRCurrent)
                        $dateFinAffectationsAGR = $dateFinAffectationsAGRCurrent;

                    // Save them
                    $agent->setCodeUOAffectationsAGR(\implode('|', \array_filter($codeUOAffectationsAGR)));
                    $agent->setDateDebutAffectationsAGR($dateDebutAffectationsAGR);
                    $agent->setDateFinAffectationsAGR($dateFinAffectationsAGR);
                    $this->em->persist($agent);
                    $this->em->flush();
                    
                }
                
                if ($loggerMode !== 'file') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }
            }
        } else {
            if ($loggerMode === 'file') {
                $this->logger->error('No agreement');
            } else {
                $io->error('No agreement');
            }
        }
    

        


        if ($loggerMode === 'file') {
            $this->logger->info('Done in ' . (time() - $start) . 's');
        } else {
            // ensures that the progress bar is at 100%
            $progressBar->finish();
            
            $io->success('Sync the agreements from GEISHA was successfully done in ' . (time() - $start) . 's');
        }        
        
        return 0;
    }
}