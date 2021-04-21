<?php

namespace App\Repository;

use App\Entity\RemboursementAbonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RemboursementAbonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method RemboursementAbonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method RemboursementAbonnement[]    findAll()
 * @method RemboursementAbonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemboursementAbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RemboursementAbonnement::class);
    }

    // /**
    //  * @return RemboursementAbonnement[] Returns an array of RemboursementAbonnement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RemboursementAbonnement
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function nbrRemboursement($id)
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.montant)')
            ->andWhere('r.idAbonnement = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
