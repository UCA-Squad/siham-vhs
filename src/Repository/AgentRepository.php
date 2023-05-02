<?php
namespace App\Repository;

use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    /**
     * @return Agent[]
     */
    public function findAllWithManyHIE()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Agent a
            WHERE a.codeUOAffectationsHIE LIKE \'%|%\'
            ORDER BY a.lastUpdate DESC, a.id'
        );

        // returns an array of Product objects
        return $query->getResult();
    }


    /**
     * @return Agent[]
     */
    public function findAllWithHIEAndNoFUN()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Agent a
            WHERE (a.codeUOAffectationsFUN IS NULL OR a.codeUOAffectationsFUN = \'\')
            AND a.codeUOAffectationsHIE IS NOT NULL AND a.codeUOAffectationsHIE != \'\'
            ORDER BY a.lastUpdate DESC, a.id'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Agent[]
     */
    public function findAllWithHIEAndNoADR()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Agent a
            WHERE (a.codeUOAffectationsADR IS NULL OR a.codeUOAffectationsADR = \'\')
            AND a.codeUOAffectationsHIE IS NOT NULL AND a.codeUOAffectationsHIE != \'\' AND a.codeUOAffectationsHIE != \'U0A000000L\'
            AND a.codePIP NOT LIKE \'%HB%\'
            AND a.codeCategoryPopulationType != 5
            AND a.codePosteAffectation != \'\' AND a.codePosteAffectation IS NOT NULL
            AND a.codePositionStatutaire = \'AC\'
            ORDER BY a.lastUpdate DESC, a.id'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Agent[]
     */
    public function findAllWithFUNAndNoHIE()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Agent a
            WHERE (a.codeUOAffectationsHIE IS NULL OR a.codeUOAffectationsHIE = \'\')
            AND a.codeUOAffectationsFUN IS NOT NULL AND a.codeUOAffectationsFUN != \'\'
            ORDER BY a.lastUpdate DESC, a.id'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Agent[]
     */
    public function findAllWithGenericValues()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Agent a
            WHERE (a.codeUOAffectationsHIE LIKE \'%UO_REP%\'
            OR a.codeUOAffectationsFUN LIKE \'%UO_REP%\'
            OR a.codePosteAffectation LIKE \'%POSTE_REP%\'
            OR a.codeEmploiAffectation LIKE \'%EMP_REP%\'
            OR a.codePopulationType = \'00000\'
            ) AND a.codePositionStatutaire != \'NS\'
            ORDER BY a.lastUpdate DESC, a.id'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
}