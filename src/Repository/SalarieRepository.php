<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParcoursRepository
 *
 * @author christophe
 */
class SalarieRepository extends EntityRepository {
    
    /**
     * recupere les info des salaries
     * et la somme des km parcouru pour l'annee fournie
     * la somme des indemnisations pour l'annee fournie
     * @param string $an
     *
     * @return array
     */
    public function findInfoSalarieNbKmIndem($an,$idUser=NULL)
    {
        if(is_null($idUser)){//pour tous les salariés
            return $this
                    ->createQueryBuilder('s')
                    ->leftJoin('IsenBackOfficeBundle:Parcours', 'p', 'WITH', 'p.idSalarie = s.id')
                    ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                    ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                    ->where('p.annee = :an') 
                    ->setParameter('an', $an)
                    ->groupBy('s.id')
                    ->getQuery()
                    ->execute()
                    ;
        }else{//pour le salarié fourni
            return $this
                    ->createQueryBuilder('s')
                    ->leftJoin('IsenBackOfficeBundle:Parcours', 'p', 'WITH', 'p.idSalarie = s.id')
                    ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                    ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                    ->where('p.annee = :an') 
                    ->andWhere('s.id = :idsalarie')
                    ->setParameter('idsalarie', $idUser)
                    ->setParameter('an', $an)
                    ->groupBy('s.id')
                    ->getQuery()
                    ->execute()
                    ;
        }
        
        
    }
    
    /**
     * cherche les parcours non clots 
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursNonClot()
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.cloture = 0')
            ->orderBy('p.annee, p.idMois, p.idSalarie')
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * cherche les parcours pour une annee et un mois 
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursAnMois($mois, $an)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.annee = :an')
            ->andWhere('p.idMois = :mois')
            ->setParameter('an', $an)
            ->setParameter('mois', $mois)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }

    /* POUR UN UTILISATEUR PARTICULIER */
    /**
     * cherche les parcours pour une annee et un mois 
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursUserAnMois($mois, $an,$idUser)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.annee = :an')
            ->andWhere('p.idMois = :mois')
            ->andWhere('p.idSalarie = :idsalarie')
            ->setParameter('an', $an)
            ->setParameter('mois', $mois)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }
    
    /**
     * cherche pour un salarié
     *  les parcours non clots pour une année particulière
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursUserNonClotAn($idUser,$an)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.cloture = 0')
            ->andWhere('p.annee = :an')
            ->andWhere('p.idSalarie = :idsalarie')
            ->orderBy('p.annee, p.idMois, p.idSalarie')
            ->setParameter('an', $an)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }
    
    /**
     * cherche pour un salarié
     *  les parcours clots non validés pour une année particulière
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursUserNonValidAn($idUser,$an)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.validation = 0')
            ->andWhere('p.cloture = 1')
            ->andWhere('p.annee = :an')
            ->andWhere('p.idSalarie = :idsalarie')
            ->orderBy('p.annee, p.idMois, p.idSalarie')
            ->setParameter('an', $an)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }
    
    /**
     * cherche pour un salarié
     *  les parcours clots et validés pour une année particulière
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursUserValidAn($idUser,$an)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.validation = 1')
            //->andWhere('p.cloture = 1')
            ->andWhere('p.annee = :an')
            ->andWhere('p.idSalarie = :idsalarie')
            ->orderBy('p.annee, p.idMois, p.idSalarie')
            ->setParameter('an', $an)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }
    
    /**
     * cherche les parcours pour un mois et une annee
     * SELECT p.*, sum(pd.indemnisation) 
    FROM `parcours` p 
    LEFT JOIN  `parcours_date` pd ON pd.id_parcours = p.id
    group by p.id
     * 
     * >leftJoin('AppBundle:LocationToJobOffer', 'u', 'WITH', 'u.offerId = p.offerId')
                ->addSelect('u.cityName')
     *  
     * @param string $ville
     *
     * @return array
     */
    public function findParcoursDateMois($an,$idMois,$nbResult)
    {
        $requete =  $this->createQueryBuilder('p')
                        ->leftJoin('IsenBackOfficeBundle:ParcoursDate', 'pd', 'WITH', 'pd.idParcours = p.id')
                        //->leftJoin('pd.id', 'pd')
                        ->addSelect('SUM(pd.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(pd.indemnisation) as totIndemnisation')
                        ->where('p.validation = 1')
                        ->andWhere('p.annee = :an') 
                        ->orderBy('p.annee, p.idMois, p.idSalarie')
                        ->setParameter('an', $an)
                        ->groupBy('p.id')
                        ;
        
        if(!is_null($idMois)){
            $requete           
            ->andWhere('p.idMois = :idMois')                            
            ->setParameter('idMois', $idMois)            
            ;
        }
        
        if(!is_null($nbResult)){
            $requete           
            ->setMaxResults($nbResult)            
            ;
        }
        
        return $requete->getQuery()->execute();
        
    }
    
    public function findGlobal($nbResult)
    {
        $requete =  $this->createQueryBuilder('p')
                        ->leftJoin('IsenBackOfficeBundle:ParcoursDate', 'pd', 'WITH', 'pd.idParcours = p.id')
                        ->addSelect('SUM(pd.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(pd.indemnisation) as totIndemnisation')

                        ->orderBy('p.annee, p.idMois, p.idSalarie')

                        ->groupBy('p.id')
                        ;
        
       
        
        return $requete->getQuery()->execute();
        
    }
    
    public function findParcoursDateMoisGlob($an,$idMois,$nbResult)
    {
        $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:ParcoursDate', 'pd', 'WITH', 'pd.idParcours = p.id')
                        //->leftJoin('pd.id', 'pd')
                        //->addSelect('SUM(pd.nbKmEffectue) as nbKmEffectue')
                        //->addSelect('SUM(pd.indemnisation) as totIndemnisation')
                        ->where('p.validation = 1')
                        ->andWhere('p.annee = :an') 
                        ->orderBy('p.annee, p.idMois, p.idSalarie')
                        ->setParameter('an', $an)
                        ->groupBy('p.id')
                        ;
        
        if(!is_null($idMois)){
            $requete           
            ->andWhere('p.idMois = :idMois')                            
            ->setParameter('idMois', $idMois)            
            ;
        }
        
        if(!is_null($nbResult)){
            $requete           
            ->setMaxResults($nbResult)            
            ;
        }
        
        return $requete->getQuery()->execute();
        
    }
    
    public function findWithNb($nbResult)
    {
        $requete =  $this->createQueryBuilder('p')
                        ->orderBy('p.annee, p.idMois, p.idSalarie')
                        ;
        
        if(!is_null($nbResult)){
            $requete           
            ->setMaxResults($nbResult)            
            ;
        }
        
        return $requete->getQuery()->execute();
        
    }
}
