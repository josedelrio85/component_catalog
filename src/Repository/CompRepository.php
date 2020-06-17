<?php

namespace App\Repository;

use App\Entity\Comp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comp[]    findAll()
 * @method Comp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comp::class);
    }

    // /**
    //  * @return Comp Returns a Comp object
    //  */
    // public function findBy($value): ?Comp
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }


    /**
     * @return Comp[] Returns an array of Comp objects
     */
    public function getCompsByCat($value)
    {
      return $this->createQueryBuilder('c')
        ->andWhere('c.idCategory = :val')
        ->setParameter('val', $value)
        ->orderBy('c.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
    }

    /*
    public function findOneBySomeField($value): ?Comp
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
