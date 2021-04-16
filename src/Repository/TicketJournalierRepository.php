<?php

namespace App\Repository;

use App\Entity\TicketJournalier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketJournalier|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketJournalier|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketJournalier[]    findAll()
 * @method TicketJournalier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketJournalierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketJournalier::class);
    }

    // /**
    //  * @return TicketJournalier[] Returns an array of TicketJournalier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketJournalier
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
