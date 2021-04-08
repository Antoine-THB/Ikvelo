<?php
namespace App\Repository;

use App\Entity\Entreprise;
use App\Entity\Salarie;
use App\Entity\Service;
use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VilleRepository
 *
 * @author antoine
 */
class BilanRepository extends EntityRepository {
    //put your code here
    

    public function findParcours($varAnnee,$varIdMois,$nbResult, $varIdService, $varIdEntreprise, $varIdSalarie)
    {
        $requete = $this
            ->createQueryBuilder('p')
            ->leftJoin(Salarie::class, 'salarie', 'WITH', 'salarie.id = p.idSalarie')
            ->leftJoin(Service::class, 'service', 'WITH', 'service.id = salarie.idService')
            ->leftJoin(Entreprise::class, 'entreprise', 'WITH', 'entreprise.id = salarie.idEntreprise')
            ->where('p.validation = 1')

            ->orderBy('p.annee, p.idMois, p.idSalarie')
            ->groupBy('p.id')
            ;

            if(!is_null($varAnnee)){
                $requete           
                ->andWhere('p.annee = :an')                            
                ->setParameter('p.annee', $varAnnee)            
                ;
            }

            if(!is_null($varIdMois)){
                $requete           
                ->andWhere('p.idMois = :idMois')                            
                ->setParameter('idMois', $varIdMois)            
                ;
            }

            if(!is_null($varIdService)){
                $requete           
                ->andWhere('salarie.idService = :idService')                            
                ->setParameter('idService', $varIdService)            
                ;
            }

            if(!is_null($varIdEntreprise)){
                $requete           
                ->andWhere('salarie.idEntreprise = :idEntreprise')                            
                ->setParameter('idEntreprise', $varIdEntreprise)            
                ;
            }

            if(!is_null($varIdSalarie)){
                $requete           
                ->andWhere('salarie.id = :idSalarie')                            
                ->setParameter('idSalarie', $varIdSalarie)            
                ;
            }


            if(!is_null($nbResult)){
                $requete           
                ->setMaxResults($nbResult)            
                ;
            }

            return $requete->getQuery()->execute();
    }

}
