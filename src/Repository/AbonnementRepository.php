<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    // /**
    //  * @return Abonnement[] Returns an array of Abonnement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonnement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function UserAnIndemnisation($idUser,$an)
    {   
        $var = "a.date_debut BETWEEN '".$an."-01-01' AND '".$an."-12-31'";
        return $this
            ->createQueryBuilder('a')
            ->select('SUM(a.indemnisation)')
            ->andWhere($var)
            ->andWhere('a.id_salarie = :idsalarie')
            // ->setParameter('an', $an)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findWithNbAndValidation($nbResult, $varValidation)
    {
        $requete =  $this->createQueryBuilder('a')
                        ->orderBy('a.date_debut','DESC')
                        ->orderBy('a.id_salarie')
                        ;
        
        if(!is_null($nbResult)){
            $requete           
            ->setMaxResults($nbResult)            
            ;
        }

        if($varValidation === "Validé"){
            $requete
            ->andWhere('a.validation =  1')
            ;         
        }
        if($varValidation === "Non validé"){
            $requete
            ->andWhere('a.validation =  0')
            ;         
        }
        
        return $requete->getQuery()->execute();  
    }

    /**
     * Abonnenment non validés
     */
    public function AboNonValid($idUser)
    {
        //Récupération de la date pour faire une condition
        $madate = new \DateTime();
        $an = $madate->format('Y');
        $m = $madate->format('m');
        $d = $madate->format('d');

        $vardebut = "a.date_debut <= '".$an."-".$m."-".$d."'";
        $varfin = "a.date_fin >= '".$an."-".$m."-".$d."'";
        return $this
            ->createQueryBuilder('a')
            ->where('a.id_salarie = :idsalarie')
            ->andWhere('a.validation = 0')
            ->andWhere($vardebut)
            ->andWhere($varfin)
            ->orderBy('a.date_debut')
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->setParameter('idsalarie', $idUser)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * Abonnenment validés
     */
    public function AboValid($idUser)
    {
        //Récupération de la date pour faire une condition
        $madate = new \DateTime();
        $an = $madate->format('Y');
        $m = $madate->format('m');
        $d = $madate->format('d');
        $vardebut = "a.date_debut <= '".$an."-".$m."-".$d."'";
        $varfin = "a.date_fin >= '".$an."-".$m."-".$d."'";

        return $this
            ->createQueryBuilder('a')
            ->where('a.id_salarie = :idsalarie')
            ->andWhere('a.validation = 1')
            ->andWhere($vardebut)
            ->andWhere($varfin)
            ->orderBy('a.date_debut')
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->setParameter('idsalarie', $idUser)
            ->getQuery()
            ->execute()
            ;
    }
}
