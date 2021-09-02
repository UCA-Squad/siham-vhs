<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RouterInterface;
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
    private $sihamEm;
    private $logger;
    private $mailer;
    private $router;

    private $wsRestartCounter;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, MailerInterface $mailer, RouterInterface $router)
    {
        parent::__construct();
        $this->em = $doctrine->getManager();// siham_vhs by default;
        $this->sihamEm = $doctrine->getManager('siham');
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->router = $router;
    }   

    protected function configure() {
        $this
         // configure options
        ->addOption('logger', null, InputOption::VALUE_OPTIONAL, 'The logger mode: "console" (by default) or "file".', 'console')
        ->addOption('from-date', null, InputOption::VALUE_OPTIONAL, '"all" or date in "Y-m-d" format (date of the day by default)', date('Y-m-d'))
        ->addOption('matricule', null, InputOption::VALUE_OPTIONAL, 'set a SIHAM id or a list separated by a comma', null)

        // the short description shown while running "php bin/console list"
        ->setDescription('Sync all users from SIHAM...')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to import users from SIHAM...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $start = microtime(true);
        ini_set('default_socket_timeout', 600);

        $loggerMode = $input->getOption('logger');
        $fromDate = $input->getOption('from-date');
        $matricules = $input->getOption('matricule');
        $startObservationDate = new \DateTime($fromDate!= 'all' ? $fromDate : null);
        $startObservationDate->modify('-' . (int) $_ENV['DAYS_OBSERVATION_RETROSPECTIVE_DATE'] . ' days');// to make a retrospective relaunch
        $considerationDate = new \DateTime();
        $endObservationDate = clone $considerationDate;
        $considerationDate->modify('+' . (int) $_ENV['DAYS_OBSERVATION_START_DATE'] . ' days'); // the sync is launched the evening
        $endObservationDate->modify('+' . (int) $_ENV['DAYS_OBSERVATION_END_DATE'] . '  days'); // to include the future contracts
        $maxObservationDate = new \DateTime($_ENV['SIHAM_WS_DATE_MAX']); // max date for SIHAM instead of empty or null when no end date ...
        $minObservationDate = new \DateTime($_ENV['SIHAM_WS_DATE_NULL']); // an other date for SIHAM that mean empty or null when no end date ...

        $emailSIContent = null;
        $emailRHContent = null;

        $connSiham = $this->sihamEm->getConnection();

        if ($loggerMode === 'file') {
            $this->logger->info('Start sync agents from SIHAM...');
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->newLine();
            $io->write('Start sync agents from SIHAM... ');
        }

        $listAgentsWS = new ListeAgentsWebService();

        #region Call SIHAM WS to retrieve updated or all agents
        $listAgents = [];
        if (empty($matricules)) {
            if ($fromDate === 'all') {
                $startTempo = microtime(true);
                $listAgents = $listAgentsWS->getListAgentsByName('%');
                if ($loggerMode === 'file') {
                    $this->logger->info('with all agents', ['ws' => 'ListeAgentsWebService', 'method' => 'recupListeAgents', 'duration' => number_format(microtime(true) - $startTempo, 3) . 's']);
                } else {
                    $io->write(' with recupListAgents... ');
                }
            } else {
                $startTempo = microtime(true);
                $listAgents = $listAgentsWS->getListAgentsUpdated($startObservationDate->format('Y-m-d'));
                if ($loggerMode === 'file') {
                    $this->logger->info('with updated agents at ' . $fromDate, ['ws' => 'ListeAgentsWebService', 'method' => 'recupAgentsModifies', 'duration' => number_format(microtime(true) - $startTempo, 3) . 's']);
                } else {
                    $io->write(' with updated agents at ' . $fromDate . '... ');
                }
            }
            // Warning or bad response from SIHAM webservices
            if (!isset($listAgents->return)) {
                if ($loggerMode === 'file') {
                    $this->logger->error('No response from WebService OR no agent', ['ws' => 'ListeAgentsWebService', 'cause' => 'empty response']);
                } else {
                    $io->error('No response from WebService');
                }
                
                $emailSIContent.= 'Le webservice <i>ListeAgentsWebService</i> avec la méthode <b>' . ($fromDate === 'all' ? 'recupListeAgents' : 'recupAgentsModifies') . '</b> n\'a rien retourné'; 
                $emailRHContent.= 'Aucun agent ' . ($fromDate === 'all' ? null : ('modifié le ' . $fromDate)) . ' n\'a pu être récupéré';
                if (!empty($emailSIContent)) $this->sendSIEmail($emailSIContent);
                if (!empty($emailRHContent)) $this->sendRHEmail($emailRHContent);

                return 0;
            }

            // Keep matricules, no more need other data
            $listAgents = is_array($listAgents->return) ? $listAgents->return : [$listAgents->return];
            $listAgents = \array_column($listAgents, 'matricule');
    
            #region Call SIHAM WS to retrieve due term agents
            $startTempo = microtime(true);
            $dueTermAgents = $listAgentsWS->getListAgentsDueTerm($startObservationDate->format('Y-m-d'), $endObservationDate->format('Y-m-d'));
            if ($loggerMode === 'file') {
                $this->logger->info('add agents with due terms at ' . $endObservationDate->format('Y-m-d'), ['ws' => 'ListeAgentsWebService', 'method' => 'recupAgentsEcheance', 'duration' => number_format(microtime(true) - $startTempo, 3) . 's']);
            } else {
                $io->writeln('add agents with due terms at ' . $endObservationDate->format('Y-m-d'));
            }
            if (isset($dueTermAgents->return)) {
                // And add them to the previous list
                $dueTermAgents = is_array($dueTermAgents->return) ? $dueTermAgents->return : [$dueTermAgents->return];
                $listAgents = \array_merge($listAgents, \array_column($dueTermAgents, 'matricule'));
                $listAgents = \array_unique($listAgents);
            } else {
                if ($loggerMode === 'file') {
                    $this->logger->warning('no agent with due terms', ['ws' => 'ListeAgentsWebService', 'cause' => 'empty response']);
                } else {
                    $io->warning('No agent with due terms');
                }
            }
            #endregion
        } else {
            $listAgents = \explode(',', $matricules);
            if ($loggerMode === 'file') {
                $this->logger->info('with a matricules list', ['webservice' => 'no', 'matricules' => $matricules]);
            } else {
                $io->writeln(' with a matricules list');
            }
        }
        #endregion
        

        if (!empty($listAgents)) {
            $numberOfUsers = count($listAgents);
            if ($loggerMode === 'file') {
                $this->logger->info($numberOfUsers . ' agents found');
            } else {
                $io->writeln(\sprintf(' <info>%s</info> agents found', $numberOfUsers));
                // creates a new progress bar
                $progressBar = new ProgressBar($io, $numberOfUsers);
                // starts and displays the progress bar
                $progressBar->start();
            }

            $timeoutCounter = 1;
            $this->wsRestartCounter = 0;
            define('TIMEOUT_MAX_COUNTER',       (int) $_ENV['TIMEOUT_MAX_COUNTER']);
            define('TIMEOUT_MAX_DURATION',      (int) $_ENV['TIMEOUT_MAX_DURATION']);
            define('TIMEOUT_PAUSE_DURATION',    (int) $_ENV['TIMEOUT_PAUSE_DURATION']);
            define('BREAK_PAUSE_COUNTER',       (int) $_ENV['BREAK_PAUSE_COUNTER']);
            define('BREAK_PAUSE_DURATION',      (int) $_ENV['BREAK_PAUSE_DURATION']);
            define('BREAK_LONG_PAUSE_COUNTER',  (int) $_ENV['BREAK_LONG_PAUSE_COUNTER']);
            define('BREAK_LONG_PAUSE_DURATION', (int) $_ENV['BREAK_LONG_PAUSE_DURATION']);
            define('SIHAM_WS_RESTART_DURATION', (int) $_ENV['SIHAM_WS_RESTART_DURATION']);
            define('SIHAM_WS_RESTART_MAX',      (int) $_ENV['SIHAM_WS_RESTART_MAX']);

            $dossierAgentWS = new DossierAgentWebService();
            $listProcessedAgents = [];
            foreach($listAgents as $agentSihamId) {
                // retrieve agent
                $agent = $this->em->getRepository(Agent::class)->findOneByMatricule($agentSihamId);
                if ($loggerMode === 'file') {
                    $this->logger->info('Get agent ' . $agentSihamId . ' from VHS database', ['found' => ($agent ? 'yes => UPDATE' : 'no => CREATE')]);
                }
                // Or create
                if (!$agent) {
                    $agent = new Agent();
                    $agent->setMatricule($agentSihamId);
                }

                #region Call SIHAM WS to get personal data
                $startTempo = microtime(true);
                $personalData = $dossierAgentWS->getPersonalData($agentSihamId, $considerationDate->format('Y-m-d'), $endObservationDate->format('Y-m-d'));
                // restart WS or exit if X timeout achieved
                $duration = microtime(true) - $startTempo;
                if ($duration > TIMEOUT_MAX_DURATION) {
                    if ($loggerMode === 'file') {
                        $this->logger->warning('Duration exceeded by ' . TIMEOUT_MAX_DURATION . 's', ['ws' => 'DossierAgentWebService', 'method' => 'recupDonneesPersonnelles', 'cause' => 'timeout', 'duration' => number_format($duration, 3) . 's']);
                    } else {
                        $io->warning('Duration ' . number_format($duration, 3) . 's exceeded by ' . TIMEOUT_MAX_DURATION . 's for recupDonneesPersonnelles');
                    }
                    if ($timeoutCounter > TIMEOUT_MAX_COUNTER) {
                        if ($this->restartWS()) {
                            if ($loggerMode === 'file') {
                                $this->logger->warning('WS restarted', ['ws' => 'DossierAgentWebService', 'method' => 'recupDonneesPersonnelles', 'cause' => $timeoutCounter . ' timeout achieved']);
                            } else {
                                $io->warning('WS restarted , ' . $timeoutCounter . ' timeout achieved');
                            }
                            $emailSIContent.= 'Le WS <b>DossierAgentWebService</b> a été redémarré car ' . $timeoutCounter . ' timeout jusqu\'à <b>recupDonneesPersonnelles</b> pour l\'agent ' . $agentSihamId . ' (réinitialisation du compteur à 1).<br>';
                            $timeoutCounter = 1;
                        } else {
                            if ($loggerMode === 'file') {
                                $this->logger->error('Sync agent interrupt', ['cause' => $timeoutCounter . ' timeout achieved']);
                            } else {
                                $io->error('Sync agent interrupt, ' . $timeoutCounter . ' timeout achieved');
                            }
                            $listUnprocessedAgents = \array_diff($listAgents, $listProcessedAgents);
                            $emailSIContent.= 'La synchronisation des agents a été arrêté après <b>' . $timeoutCounter . '</b> tentatives de pause de <b>' . TIMEOUT_PAUSE_DURATION . 's</b> suite a des <i>timeout</i> ou des temps de récupération dépassant les <b>' . TIMEOUT_MAX_DURATION . 's</b>'; 
                            $emailRHContent.= 'La synchronisation des agents s\'est arrêtée suite a des temps de réponse trop long des webservices.<br>';
                            $emailRHContent.= '<b>' . count($listUnprocessedAgents) . '</b>/' . count($listAgents) . ' matricules n\'ont pas été traités:<br>';
                            $emailRHContent.= \implode('<br>', $listUnprocessedAgents) . '<br>';
                            if (!empty($emailSIContent)) $this->sendSIEmail($emailSIContent);
                            if (!empty($emailRHContent)) $this->sendRHEmail($emailRHContent);
                            return 0;
                        }
                    }
                    $this->logger->info('Have a break for ' . TIMEOUT_PAUSE_DURATION . 's ...', ['cause' => $timeoutCounter . ' attempts achieved']);
                    \sleep(TIMEOUT_PAUSE_DURATION);
                    $timeoutCounter++;
                } else {
                    // set data if returned
                    if (isset($personalData->return)) {
                        $agent->addPersonalData($personalData->return);
                        if ($loggerMode === 'file') {
                            $this->logger->info('- receive personal data' . \str_repeat('&nbsp;', 6), ['duration' => number_format(microtime(true) - $startTempo, 3) . 's']);
                        }
                    } else {
                        if ($loggerMode === 'file') {
                            $this->logger->warning('- no personal data' . \str_repeat('&nbsp;', 6), ['ws' => 'DossierAgentWebService', 'method' => 'recupDonneesPersonnelles', 'cause' => 'empty response']);
                        } else {
                            $io->warning('No personal data for ' . $agentSihamId);
                        }
                        $emailSIContent.= 'recupDonneesPersonnelles vide pour l\'agent ' . $agentSihamId . '.<br>';
                    }
                }
                #endregion
        

                #region Call SIHAM WS to get administrative data
                $startTempo = microtime(true);
                $administrativeData = $dossierAgentWS->getAdministrativeData($agentSihamId, $considerationDate->format('Y-m-d'), $maxObservationDate->format('Y-m-d'));
                // restart WS or exit if X timeout achieved
                $duration = microtime(true) - $startTempo;
                if ($duration > TIMEOUT_MAX_DURATION) {
                    if ($loggerMode === 'file') {
                        $this->logger->warning('Duration exceeded by ' . TIMEOUT_MAX_DURATION . 's', ['ws' => 'DossierAgentWebService', 'method' => 'recupDonneesAdministratives', 'cause' => 'timeout', 'duration' => number_format($duration, 3) . 's']);
                    } else {
                        $io->warning('Duration ' . number_format($duration, 3) . 's exceeded by ' . TIMEOUT_MAX_DURATION . 's for recupDonneesAdministratives');
                    }
                    if ($timeoutCounter > TIMEOUT_MAX_COUNTER) {
                        if ($this->restartWS()) {
                            if ($loggerMode === 'file') {
                                $this->logger->warning('WS restarted', ['ws' => 'DossierAgentWebService', 'method' => 'recupDonneesAdministratives', 'cause' => $timeoutCounter . ' timeout achieved']);
                            } else {
                                $io->warning('WS restarted , ' . $timeoutCounter . ' timeout achieved');
                            }
                            $emailSIContent.= 'Le WS <b>DossierAgentWebService</b> a été redémarré car ' . $timeoutCounter . ' timeout jusqu\'à <b>recupDonneesAdministratives</b> pour l\'agent ' . $agentSihamId . ' (réinitialisation du compteur à 1).<br>';
                            $timeoutCounter = 1;
                        } else {
                            if ($loggerMode === 'file') {
                                $this->logger->error('Sync agent interrupt', ['cause' => $timeoutCounter . ' timeout achieved']);
                            } else {
                                $io->error('Sync agent interrupt, ' . $timeoutCounter . ' timeout achieved');
                            }
                            $listUnprocessedAgents = \array_diff($listAgents, $listProcessedAgents);
                            $emailSIContent.= 'La synchronisation des agents a été arrêté après <b>' . $timeoutCounter . '</b> tentatives de pause de <b>' . TIMEOUT_PAUSE_DURATION . 's</b> suite a des <i>timeout</i> ou des temps de récupération dépassant les <b>' . TIMEOUT_MAX_DURATION . 's</b>'; 
                            $emailRHContent.= 'La synchronisation des agents s\'est arrêtée suite a des temps de réponse trop long des webservices.<br>';
                            $emailRHContent.= '<b>' . count($listUnprocessedAgents) . '</b>/' . count($listAgents) . ' matricules n\'ont pas été traités:<br>';
                            $emailRHContent.= \implode('<br>', $listUnprocessedAgents) . '<br>';
                            if (!empty($emailSIContent)) $this->sendSIEmail($emailSIContent);
                            if (!empty($emailRHContent)) $this->sendRHEmail($emailRHContent);
                            return 0;
                        }
                    }
                    $this->logger->info('Have a break for ' . TIMEOUT_PAUSE_DURATION . 's ...', ['cause' => $timeoutCounter . ' attempts achieved']);
                    \sleep(TIMEOUT_PAUSE_DURATION);
                    $timeoutCounter++;
                } else {
                    // set data if returned
                    if (isset($administrativeData->return)) {
                        $agent->addAdministrativeData($administrativeData->return, $considerationDate, $endObservationDate);
                        if ($loggerMode === 'file') {
                            $this->logger->info('- receive administrative data', ['duration' => number_format($duration, 3) . 's']);
                        }
                    } else {
                        if ($loggerMode === 'file') {
                            $this->logger->warning('- no administrative data', ['ws' => 'DossierAgentWebService', 'method' => 'recupDonneesAdministratives', 'cause' => 'empty response']);
                        } else {
                            $io->warning('No administrative data for ' . $agentSihamId);
                        }
                        $emailSIContent.= 'recupDonneesPersonnelles vide pour l\'agent ' . $agentSihamId . '.<br>';
                    }
                }
                #endregion


                #region Call SIHAM db to get population type*
                $startTempo = microtime(true);
                $codePopulationType = NULL;
                $codeCategoryPopulationType = NULL;
                $codeSubCategoryPopulationType = NULL;
                $sqlSihamPopulationType = 'SELECT CATEGO, SSCATE, POPULA, TO_CHAR(DTEF00, \'YYYY-MM-DD\') AS DTEF00, TO_CHAR(DATXXX, \'YYYY-MM-DD\') AS DATXXX FROM HR.ZYYP 
                WHERE NUDOSS IN (SELECT NUDOSS FROM HR.ZY00 WHERE matcle = :matricule)
                AND TO_DATE(:endObservationDate, \'YYYY-MM-DD\') >= DTEF00
                AND TO_DATE(:startObservationDate, \'YYYY-MM-DD\') < DATXXX 
                ORDER BY DTEF00';
                $stmtSihamPopulationType = $connSiham->prepare($sqlSihamPopulationType);
                $stmtSihamPopulationType->bindValue('matricule', $agent->getMatricule());
                $stmtSihamPopulationType->bindValue('startObservationDate', $considerationDate->format('Y-m-d'));
                $stmtSihamPopulationType->bindValue('endObservationDate', $endObservationDate->format('Y-m-d'));
                $stmtSihamPopulationType->execute();
                $resPopulationTypes = $stmtSihamPopulationType->fetchAll();
                if (!empty($resPopulationTypes)) {
                    if ($loggerMode === 'file') {
                        $this->logger->info('- receive population type' . \str_repeat('&nbsp;', 4), ['duration' => number_format(microtime(true) - $startTempo, 3) . 's']);
                    }
                    // Loop on each agreement to set it to the agent
                    foreach ($resPopulationTypes as $resPopulationType) {
                        $startPopulationTypeDate = new \DateTime(\substr($resPopulationType['DTEF00'],0,10));
                        $endPopulationTypeDate = new \DateTime(\substr($resPopulationType['DATXXX'],0,10));
                        if (($considerationDate >= $startPopulationTypeDate && $considerationDate <= $endPopulationTypeDate) 
                        || (!empty($agent->getDateDebutAffectationsHIE()) && $agent->getDateDebutAffectationsHIE() >= $startPopulationTypeDate)) {
                            $codePopulationType = $resPopulationType['POPULA'];
                            $codeCategoryPopulationType = $resPopulationType['CATEGO'];
                            $codeSubCategoryPopulationType = $resPopulationType['SSCATE'];
                        }
                    }
                } else {
                    if ($loggerMode === 'file') {
                        $this->logger->info('- no population type' . \str_repeat('&nbsp;', 7), ['ws' => 'no', 'db' => 'SIHAM']);
                    }
                }
                $agent->setCodePopulationType($codePopulationType);
                $agent->setCodeCategoryPopulationType($codeCategoryPopulationType);
                $agent->setCodeSubCategoryPopulationType($codeSubCategoryPopulationType);
                #endregion

                // Save it
                $this->em->persist($agent);
                $this->em->flush();
                $listProcessedAgents[] = $agentSihamId;
    
                if ($loggerMode !== 'file') {
                    // advances the progress bar 1 unit
                    $progressBar->advance();
                }

                // Take a break for webservice :-( 
                // Temporally disable since VM up memory to 6Go
                // Re-used ...
                $counterTempo = count($listProcessedAgents);
                if ($counterTempo % BREAK_PAUSE_COUNTER == 0) {
                    if ($counterTempo % BREAK_LONG_PAUSE_COUNTER == 0) {
                        if ($loggerMode === 'file') {
                            $this->logger->info('Have a break for ' . BREAK_LONG_PAUSE_DURATION . 's ...', ['cause' => $counterTempo . ' agents achieved']);
                        }
                        \sleep(BREAK_LONG_PAUSE_DURATION);
                    } else {
                        if ($loggerMode === 'file') {
                            $this->logger->info('Have a break for ' . BREAK_PAUSE_DURATION . 's ...', ['cause' => $counterTempo . ' agents achieved']);
                        }
                        \sleep(BREAK_PAUSE_DURATION);
                    }
                }
            }

            $duration = microtime(true) - $start;
            if ($loggerMode === 'file') {
                $this->logger->info('Done in ' . number_format($duration, 3) . 's');
            } else {
                // ensures that the progress bar is at 100%
                $progressBar->finish();
                $io->success('Sync the users from SIHAM was successfully done in ' . number_format($duration, 3) . 's');
            }

        } else {
            if ($loggerMode === 'file') {
                $this->logger->error('No response from WebService OR no agent', ['ws' => 'ListeAgentsWebService', 'cause' => 'empty response']);
            } else {
                $io->error('No response from WebService');
            }
        }
        
        if (!empty($emailSIContent)) $this->sendSIEmail($emailSIContent);
        if (!empty($emailRHContent)) $this->sendRHEmail($emailRHContent);
        
        return 0;
    }

    public function restartWS() {
        // check already sent
        if ($this->wsRestartCounter > SIHAM_WS_RESTART_MAX)
            return false;
        
        $this->wsRestartCounter++;    
        
        // exec command
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_URL, $_ENV['SIHAM_WS_URL'] . '/manager/text/reload?path=/DossierAgentWebService');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500);
        curl_setopt($ch, CURLOPT_TIMEOUT, SIHAM_WS_RESTART_DURATION);
        $headers = ['Authorization: Basic '. base64_encode($_ENV['SIHAM_TOMCAT_USERNAME'] . ':' . $_ENV['SIHAM_TOMCAT_PASSWORD'])];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($ch);

        // timeout or any error
        if (!empty(curl_error($ch)))
            return false;

        curl_close($ch);

        if (strripos($result, 'OK') === false)
            return false;

        // ws is relaunched
        return true;
    }


    public function sendSIEmail($content, $html = true) {
        $to = empty($_ENV['MAIL_TO_SI']) ? null : \explode(',', $_ENV['MAIL_TO_SI']);
        $cc = empty($_ENV['MAIL_CC_SI']) ? null : \explode(',', $_ENV['MAIL_CC_SI']);
        $subject = '[SIHAM] VHS - Rapport d\'erreur technique de Sync:Agent';
        $this->sendEmail($to, $cc, $subject, $content, $html);
    }
    public function sendRHEmail($content, $html = true) {
        $to = empty($_ENV['MAIL_TO_RH']) ? null : \explode(',', $_ENV['MAIL_TO_RH']);
        $cc = empty($_ENV['MAIL_CC_RH']) ? null : \explode(',', $_ENV['MAIL_CC_RH']);
        $subject = '[SIHAM] VHS - Rapport d\'erreur de Sync:Agent';
        $this->sendEmail($to, $cc, $subject, $content, $html);
    }
    public function sendEmail($to, $cc, $subject, $content, $html = true) {
        $email = (new Email())
            ->from($_ENV['MAIL_FROM'])
            ->subject($subject);
        if (!empty($to)) {
            $email->to(...$to);
            if (!empty($cc)) $email->cc(...$cc);

            $url = $_ENV['APP_HOST'] . $this->router->generate('sync_result', [
                'env' => $_ENV['APP_ENV'] , 
                'fileName' => $_ENV['APP_ENV'] . '.sync.agent-' . date('Y-m-d') . '.log'
            ]);
            if ($html) {
                $content.= '<br><br><a href="' . $url . '">Consulter le fichier de log</a>';
                $content.= '<br><br><i>Environnement ' . $_ENV['APP_ENV'] . '</i>';
                $email->html($content);
            } else {
                $content.= "\n\n" . 'Consulter le fichier de log sur ' . $url;
                $content.= "\n\n" . 'Environnement ' . $_ENV['APP_ENV'];
                $email->text($content);
            }
            $this->mailer->send($email);
        } 
        
    }
}