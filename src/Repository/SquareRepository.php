<?php

namespace App\Repository;

use App\Entity\Square;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Square|null find($id, $lockMode = null, $lockVersion = null)
 * @method Square|null findOneBy(array $criteria, array $orderBy = null)
 * @method Square[]    findAll()
 * @method Square[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SquareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Square::class);
    }

    // /**
    //  * @return Square[] Returns an array of Square objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    public function findOtherSquare($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.value <> :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }    
}
