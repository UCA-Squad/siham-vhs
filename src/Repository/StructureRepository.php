<?php
namespace App\Repository;

use App\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Structure::class);
    }

    /**
     * @return Product[]
     */
    public function findOneInCodeHarpege($codeHarpege)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Structure s
            WHERE FIND_IN_SET(:codeHarpege, s.codeHarpege) > 0'
        )->setParameter('codeHarpege', $codeHarpege);

        // returns an array of Product objects
        return $query->getOneOrNullResult();
    }
}