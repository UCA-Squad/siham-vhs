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

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer) {
        parent::__construct();
        $this->em = $em;
        $this->mailer = $mailer;
    }   

    protected function configure() {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Anomy detections for many use cases.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command check if agent have HIE and not FUN, double HIE, FUN and not HIE, no ADR.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
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
            $nbAgents = count($agentsWithManyHIE);
            $io->writeln(\sprintf('<info>%s</info> agents found', $nbAgents));
            $emailContent.= '== ' . $nbAgents . ' agent(s) avec plusieurs affectations HIE ==' . "\n";
            foreach ($agentsWithManyHIE as $agentWithManyHIE) {
                $display = '- ' . $agentWithManyHIE->getMatricule() . ' - ' . $this->addSpaces($agentWithManyHIE->getNomUsuel(), 20) . ' - ' . $this->addSpaces($agentWithManyHIE->getPrenom(), 20)
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
            $nbAgents = count($agentsWithHIEAndNoFUN);
            $io->writeln(\sprintf('<info>%s</info> agents found', $nbAgents));
            $emailContent.= '== ' . $nbAgents . ' agent(s) avec une affectation HIE mais aucune affectation FUN ==' . "\n";
            foreach ($agentsWithHIEAndNoFUN as $agentWithHIEAndNoFUN) {
                $display = '- ' . $agentWithHIEAndNoFUN->getMatricule() . ' - ' . $this->addSpaces($agentWithHIEAndNoFUN->getNomUsuel(), 20) . ' - ' . $this->addSpaces($agentWithHIEAndNoFUN->getPrenom(), 20)
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
            $nbAgents = count($agentsWithHIEAndNoADR);
            $io->writeln(\sprintf('<info>%s</info> agents found', $nbAgents));
            $emailContent.= '== ' . $nbAgents . ' agent(s) avec une affectation HIE mais aucune affectation ADR (hors Hébergés et Extérieurs) ==' . "\n";
            foreach ($agentsWithHIEAndNoADR as $agentWithHIEAndNoADR) {
                $display = '- ' . $agentWithHIEAndNoADR->getMatricule() . ' - ' . $this->addSpaces($agentWithHIEAndNoADR->getNomUsuel(), 20) . ' - ' . $this->addSpaces($agentWithHIEAndNoADR->getPrenom(), 20)
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
            $nbAgents = count($agentsWithFUNAndNoHIE);
            $io->writeln(\sprintf('<info>%s</info> agents found', $nbAgents));
            $emailContent.= '== ' . $nbAgents . ' agent(s) avec une affectation HIE mais aucune affectation FUN ==' . "\n";
            foreach ($agentsWithFUNAndNoHIE as $agentWithFUNAndNoHIE) {
                $display = '- ' . $agentWithFUNAndNoHIE->getMatricule() . ' - ' . $this->addSpaces($agentWithFUNAndNoHIE->getNomUsuel(), 20) . ' - ' . $this->addSpaces($agentWithFUNAndNoHIE->getPrenom(), 20)
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

        #region Agent with generic values
        $io->writeln('== Agent(s) with generic values ==');
        $agentsWithGenericValues = $this->em->getRepository(Agent::class)->findAllWithGenericValues();
        if ($agentsWithGenericValues) {
            $nbAgents = count($agentsWithGenericValues);
            $io->writeln(\sprintf('<info>%s</info> agents found', $nbAgents));
            $emailContent.= '== ' . $nbAgents . ' agent(s) avec des valeurs génériques ==' . "\n";
            foreach ($agentsWithGenericValues as $agentWithGenericValues) {
                $display = '- ' . $agentWithGenericValues->getMatricule() . ' - ' . $this->addSpaces($agentWithGenericValues->getNomUsuel(), 20) . ' - ' . $this->addSpaces($agentWithGenericValues->getPrenom(), 20)
                    . ' - ' . $this->addSpaces($agentWithGenericValues->getCodeUOAffectationsHIE(), 25)
                    . ' - ' . $this->addSpaces($agentWithGenericValues->getCodeUOAffectationsFUN(), 25)
                    . ' - ' . $this->addSpaces($agentWithGenericValues->getCodePosteAffectation(), 10)
                    . ' - ' . $this->addSpaces($agentWithGenericValues->getCodeEmploiAffectation(), 10)
                    . ' - ' . $this->addSpaces($agentWithGenericValues->getCodePopulationType(), 5)
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

        $to = \explode(',', $_ENV['MAIL_TO_RH']);
        $cc = \explode(',', $_ENV['MAIL_CC_RH']);
        if (!empty($emailContent)) {
            $email = (new Email())
                ->from($_ENV['MAIL_FROM'])
                ->to(...$to)
                ->cc(...$cc)
                ->subject('[SIHAM] VHS - Détection des anomalies')
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