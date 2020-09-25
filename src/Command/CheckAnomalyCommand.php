<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Agent;


class CheckAnomalyCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'check:anomaly';
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
    }   

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Anomy detections for many use cases.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command check if agent have HIE and not FUN, double HIE, FUN and not HIE, no ADR.')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        $emailContent = null;
        
        $io = new SymfonyStyle($input, $output);
        $io->newLine();
        $io->writeln('Start anomaly detections... ');
        $io->newLine();
    
        #region Agent with double HIE
        $io->writeln('== Agent(s) with double HIE ==');
        $agentsWithManyHIE = $this->em->getRepository(Agent::class)->findAllWithManyHIE();
        if ($agentsWithManyHIE) {
            $io->writeln(\sprintf('<info>%s</info> agents found', count($agentsWithManyHIE)));
            $emailContent.= '== Agent(s) avec plusieurs affectations HIE ==' . "\n";
            foreach ($agentsWithManyHIE as $agentWithManyHIE) {
                $display = '- ' . $agentWithManyHIE->getMatricule() . ' - ' . $this->addSpaces($agentWithManyHIE->getNomPatronymique(), 20) . ' - ' . $this->addSpaces($agentWithManyHIE->getPrenom(), 20)
                    . ' - ' . $this->addSpaces($agentWithManyHIE->getCodeUOAffectationsHIE(), 25)
                ;
                $io->writeln($display);
                $emailContent.= $display . "\n";
            }
            $emailContent.= "\n";
        } else {
            $io->writeln('no agent');
        }
        $io->newLine();
        #endregion

        #region Agent with HIE and no FUN
        $io->writeln('== Agent(s) with HIE and no FUN ==');
        $agentsWithHIEAndNoFUN = $this->em->getRepository(Agent::class)->findAllWithHIEAndNoFUN();
        if ($agentsWithHIEAndNoFUN) {
            $io->writeln(\sprintf('<info>%s</info> agents found', count($agentsWithHIEAndNoFUN)));
            $emailContent.= '== Agent(s) avec une affectation HIE mais aucune affectation FUN ==' . "\n";
            foreach ($agentsWithHIEAndNoFUN as $agentWithHIEAndNoFUN) {
                $display = '- ' . $agentWithHIEAndNoFUN->getMatricule() . ' - ' . $this->addSpaces($agentWithHIEAndNoFUN->getNomPatronymique(), 20) . ' - ' . $this->addSpaces($agentWithHIEAndNoFUN->getPrenom(), 20)
                    . ' - ' . $this->addSpaces($agentWithHIEAndNoFUN->getCodeUOAffectationsHIE(), 25)
                    . ' - ' . $this->addSpaces($agentWithHIEAndNoFUN->getCodeUOAffectationsFUN(), 25)
                ;
                $io->writeln($display);
                $emailContent.= $display . "\n";
            }
            $emailContent.= "\n";
        } else {
            $io->writeln('no agent');
        }
        $io->newLine();
        #endregion

        #region Agent with HIE and no ADR
        $io->writeln('== Agent(s) with HIE and no ADR ==');
        $agentsWithHIEAndNoADR = $this->em->getRepository(Agent::class)->findAllWithHIEAndNoADR();
        if ($agentsWithHIEAndNoADR) {
            $io->writeln(\sprintf('<info>%s</info> agents found', count($agentsWithHIEAndNoADR)));
            $emailContent.= '== Agent(s) avec une affectation HIE mais aucune affectation ADR ==' . "\n";
            foreach ($agentsWithHIEAndNoADR as $agentWithHIEAndNoADR) {
                $display = '- ' . $agentWithHIEAndNoADR->getMatricule() . ' - ' . $this->addSpaces($agentWithHIEAndNoADR->getNomPatronymique(), 20) . ' - ' . $this->addSpaces($agentWithHIEAndNoADR->getPrenom(), 20)
                    . ' - ' . $this->addSpaces($agentWithHIEAndNoADR->getCodeUOAffectationsHIE(), 25)
                    . ' - ' . $this->addSpaces($agentWithHIEAndNoADR->getCodeUOAffectationsADR(), 25)
                ;
                $io->writeln($display);
                $emailContent.= $display . "\n";
            }
            $emailContent.= "\n";
        } else {
            $io->writeln('no agent');
        }
        $io->newLine();
        #endregion

        #region Agent with FUN and no HIE
        $io->writeln('== Agent(s) with FUN and no HIE ==');
        $agentsWithFUNAndNoHIE = $this->em->getRepository(Agent::class)->findAllWithFUNAndNoHIE();
        if ($agentsWithFUNAndNoHIE) {
            $io->writeln(\sprintf('<info>%s</info> agents found', count($agentsWithFUNAndNoHIE)));
            $emailContent.= '== Agent(s) avec une affectation HIE mais aucune affectation FUN ==' . "\n";
            foreach ($agentsWithFUNAndNoHIE as $agentWithFUNAndNoHIE) {
                $display = '- ' . $agentWithFUNAndNoHIE->getMatricule() . ' - ' . $this->addSpaces($agentWithFUNAndNoHIE->getNomPatronymique(), 20) . ' - ' . $this->addSpaces($agentWithFUNAndNoHIE->getPrenom(), 20)
                    . ' - ' . $this->addSpaces($agentWithFUNAndNoHIE->getCodeUOAffectationsHIE(), 25)
                    . ' - ' . $this->addSpaces($agentWithFUNAndNoHIE->getCodeUOAffectationsFUN(), 25)
                ;
                $io->writeln($display);
                $emailContent.= $display . "\n";
            }
            $emailContent.= "\n";
        } else {
            $io->writeln('no agent');
        }
        $io->newLine();
        #endregion

        

    
                
        $io->success('Done in ' . number_format((microtime(true) - $start), 3) . 's');

        if (!empty($emailContent)) {
            $email = (new Email())
                ->from('vhs-noreply@uca.fr')
                ->cc(\implode(',', ['fabrice.monseigne@uca.fr']))
                ->subject('[SIHAM] VHS - Rapport des anomalies')
                ->text($emailContent);
    
            $this->mailer->send($email);
        }
        
        return 0;
    }

    public function addSpaces($str, $length, $html = false) {
        
        $str = str_pad($str, $length, ' ');
        // $str = str_replace('*', ($html ? '&nbsp;' : ' '), $str);

        return $str;
    }
}