<?php
namespace App\Repository;

use App\Entity\Entreprise;
use App\Entity\Parcours;
use App\Entity\ParcoursDate;
use App\Entity\Salarie;
use App\Entity\Service;
use Doctrine\ORM\EntityRepository;
use App\Repository\ParcoursDateRepository;
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
class ParcoursRepository extends EntityRepository {
    //put your code here
    
    /**
     * cherche les parcours clots non validés
     * @param string $ville
     *
     * @return array
     */
    public function listParcoursClotNonValid()
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.cloture = 1')
            ->andWhere('p.validation = 0')
            ->orderBy('p.annee, p.idMois, p.idSalarie')
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
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
    
    /**
     * cherche l'anne minimum 
     * @param string $ville
     *
     * @return array
     */
    public function getAnMin()
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p, MIN(p.annee) AS min_annee')
            ->getQuery()
            ->execute()
            ;
    }
    
    /**
     * cherche lla liste des années distinctes 
     * @param string $ville
     *
     * @return array
     */
    public function getListAn()
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.annee')
            ->distinct()    
            ->getQuery()
            ->execute()
            ;
    }
    
    /* POUR LES STAT */
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour tous les salariés
     * pour toutes les années ou une année particuliere
     */
    public function getStatAn($annee=NULL)
    {
        if(isset($annee)){
           
            $requete =  $this->createQueryBuilder('p')
                        ->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        ->addSelect('m.mois')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.annee = :an')
                        ->groupBy('p.annee,p.idMois')
                        ->setParameter('an', $annee)
                        ;


        }else{
            $requete =  $this->createQueryBuilder('p')
                        ->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        ->addSelect('m.mois')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->groupBy('p.annee,p.idMois')
                        ;
        }
       
        return $requete->getQuery()->execute();
        
    }
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour tous les salariés
     * pour toutes les années sans info sur les mois
     * en tenant compte du mois de début de période (table parametre)
     */
    public function getStatToutAn($moisRefInt)
    {
        /*
        $requete =  $this->createQueryBuilder('p')
                    //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                    ->select('p.annee')   
                    //->addSelect('m.id as mois')
                    //->addSelect('m.mois')
                    ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                    ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                    ->groupBy('p.annee')
                    ->orderBy('p.annee','DESC')
                    ;
        
       
        return $requete->getQuery()->execute();
        */
        
        $requete =  $this->createQueryBuilder('p')
                    //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                    ->select('IF(p.idMois>:moisRefInt,p.annee+1,p.annee) AS newan')   
                    //->addSelect('m.id as mois')
                    //->addSelect('m.mois')
                    ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                    ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                    ->groupBy('newan')
                    ->orderBy('newan','DESC')
                    ->setParameter('moisRefInt', $moisRefInt)
                    ;
        
       
        return $requete->getQuery()->execute();
        
        /*
        $query = $this->getEntityManager()->createQuery(
            'SELECT IF(p.idMois>11,p.annee+1,p.annee) AS newan, SUM(p.nbKmEffectue) as nbKmEffectue, SUM(p.indemnisation) as totIndemnisation
            FROM IsenBackOfficeBundle:Parcours p
            GROUP BY newan
            ORDER BY newan DESC'
        );

        $products = $query->getResult();
        */
        
    }
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale par salariés
     * pour toutes les années sans info sur les mois
     * en tenant compte du mois de début de période (table parametre)
     */
    public function getStatToutAnSal($moisRefInt)
    {
        
        
        $requete =  $this->createQueryBuilder('p')
                    ->leftJoin('IsenBackOfficeBundle:Salarie', 's', 'WITH', 's.id = p.idSalarie')
                    ->select('IF(p.idMois>:moisRefInt,p.annee+1,p.annee) AS newan')   
                    ->addSelect('s.nom as nom')
                    ->addSelect('s.prenom as prenom')
                    ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                    ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                    ->groupBy('newan')
                    ->addGroupBy('p.idSalarie')
                    ->orderBy('newan','DESC')
                    ->setParameter('moisRefInt', $moisRefInt)
                    ;
        
       
        return $requete->getQuery()->execute();
        
       
        
    }
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour tous les salariés
     * pour toutes les années sans info sur les mois
     * mais en prenant en compte le mois de depart et de fin
     */
    public function getStatToutAnMoisInit()
    {
        
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ;
        
       
        return $requete->getQuery()->execute();
        
    }
    
    /*->select('m.id as moisid')
     * recupere les moyennes 
     * sur le nombre de km parcourus 
     * et l'indemnisation totale pour tous les salariés
     * pour toutes les années 
     */
    public function getStatMoyAn()
    {
          
        $requete =  $this->createQueryBuilder('p')
                    ->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                    ->select('m.mois')
                    ->addSelect('AVG(p.nbKmEffectue) as moyNbKmEffectue')
                    ->addSelect('AVG(p.indemnisation) as moyIndemnisation')
                    ->groupBy('p.idMois')
                    ;

        return $requete->getQuery()->execute();
        
    }

    /* POUR UN UTILISATEUR PARTICULIER */
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour un salarié
     * pour toutes les années ou une année particuliere
     * avec les mois
     */
    public function getStatAnSal($annee=NULL,$idUser)
    {
        if(isset($annee)){
           
            $requete =  $this->createQueryBuilder('p')
                        ->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        ->addSelect('m.mois')
                        ->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.annee = :an')
                        ->andWhere('p.idSalarie = :idsalarie')
                        ->groupBy('p.annee,p.idMois')
                        ->setParameter('an', $annee)
                        ->setParameter('idsalarie', $idUser)
                        ;


        }else{
            $requete =  $this->createQueryBuilder('p')
                        ->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        ->addSelect('m.mois')
                        ->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->groupBy('p.annee,p.idMois')
                        ->setParameter('idsalarie', $idUser)
                        ;
        }
       
        return $requete->getQuery()->execute();
        
    }
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour un salarié
     * pour toutes les années ou une année particuliere
     * sans les mois
     */
    public function getStatAnSalSsMois($annee=NULL,$idUser)
    {
        if(isset($annee)){
           
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        //->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.annee = :an')
                        ->andWhere('p.idSalarie = :idsalarie')
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ->setParameter('an', $annee)
                        ->setParameter('idsalarie', $idUser)
                        ;


        }else{
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        //->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ->setParameter('idsalarie', $idUser)
                        ;
        }
       
        return $requete->getQuery()->execute();
        
    }
    
    
    /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour un salarié
     * pour toutes les années ou une année particuliere
     * sans les mois
     * en partant du mois initial de l'année jusquau mois de l'année AN+1
     */
    public function getStatAnSalSsMoisMsInit($idUser, $mois_deb, $mois_fin)
    {
        /*
        if(isset($annee)){
            $anneeun = $annee +1;
           
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')  
                        
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        //->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->andWhere('(p.annee = :an AND p.idMois>:mois_deb) OR (p.annee = :an1 AND p.idMois<=:mois_deb)')
                        
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ->setParameter('an', $annee)
                        ->setParameter('an1', $anneeun)
                        ->setParameter('mois_deb', $mois_deb)
                        ->setParameter('idsalarie', $idUser)
                        ;


        }else{
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        //->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ->setParameter('idsalarie', $idUser)
                        ;
        }
         * 
         */
       
        $requete =  $this->createQueryBuilder('p')
                        ->select('IF(p.idMois>:moisRefInt,p.annee+1,p.annee) AS newan')                       
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->groupBy('newan')
                        ->orderBy('newan','DESC')
                        ->setParameter('moisRefInt', $mois_deb)
                        ->setParameter('idsalarie', $idUser)
                        ;
        
        return $requete->getQuery()->execute();
        
    }
    
     /*
     * recupere le nombre de km parcourus 
     * et l'indemnisation totale pour un salarié
     * pour  année particuliere
     * sans les mois
     * en partant du mois initial de l'année jusquau mois de l'année AN+1
     */
    public function getStatAnSalSsMoisMsInitAn($annee,$idUser, $moisRefInt)
    {
        /*
        if(isset($annee)){
            $anneeun = $annee +1;
           
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')  
                        
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        //->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->andWhere('(p.annee = :an AND p.idMois>:mois_deb) OR (p.annee = :an1 AND p.idMois<=:mois_deb)')
                        
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ->setParameter('an', $annee)
                        ->setParameter('an1', $anneeun)
                        ->setParameter('mois_deb', $mois_deb)
                        ->setParameter('idsalarie', $idUser)
                        ;


        }else{
            $requete =  $this->createQueryBuilder('p')
                        //->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                        ->select('p.annee')   
                        //->addSelect('m.id as mois')
                        //->addSelect('m.mois')
                        //->addSelect('m.id as moisid')
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->groupBy('p.annee')
                        ->orderBy('p.annee','DESC')
                        ->setParameter('idsalarie', $idUser)
                        ;
        }
         * 
         */
        $an1 = $annee - 1;
        $requete =  $this->createQueryBuilder('p')
                       // ->select('IF(p.idMois>:moisRefInt,p.annee+1,p.annee) AS newan')                       
                        ->addSelect('SUM(p.nbKmEffectue) as nbKmEffectue')
                        ->addSelect('SUM(p.indemnisation) as totIndemnisation')
                        ->where('p.idSalarie = :idsalarie')
                        ->andWhere('(p.annee = :an1 AND p.idMois>:mois_deb) OR (p.annee = :an AND p.idMois<=:mois_deb)')
                        //->groupBy('newan')
                        //->orderBy('newan','DESC')
                        ->setParameter('moisRefInt', $moisRefInt)
                        ->setParameter('idsalarie', $idUser)
                        ;
        
        return $requete->getQuery()->execute();
        
    }
    
    /*
     * recupere les moyennes 
     * sur le nombre de km parcourus 
     * et l'indemnisation totale pour un salariés
     * pour toutes les années 
     */
    public function getStatMoyAnSal($idUser)
    {
          
        $requete =  $this->createQueryBuilder('p')
                    ->leftJoin('IsenBackOfficeBundle:Mois', 'm', 'WITH', 'm.id = p.idMois')
                    ->select('m.id as moisid')
                    ->addSelect('AVG(p.nbKmEffectue) as moyNbKmEffectue')
                    ->addSelect('AVG(p.indemnisation) as moyIndemnisation')
                    ->where('p.idSalarie = :idsalarie')
                    ->groupBy('p.idMois')
                    ->setParameter('idsalarie', $idUser)
                    ;

        return $requete->getQuery()->execute();
        
    }
    
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
                        ->leftJoin(ParcoursDate::class, 'pd', 'WITH', 'pd.idParcours = p.id')
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
    
    public function findGlobal($nbResult=NULL)
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
                        //->orderBy('p.annee, p.idMois, p.idSalarie','DESC,ASC,ASC')
                        ->orderBy('p.annee','DESC')
                        ->orderBy('p.idMois, p.idSalarie')
                        //->orderBy('p.annee, p.idMois, p.idSalarie','DESC,ASC,ASC')
                        //->add('orderBy', 'p.annee DESC')
                        //->add('orderBy', 'p.idMois ASC')
                        //->add('orderBy', 'p.idSalarie ASC')
                        ;
        
        if(!is_null($nbResult)){
            $requete           
            ->setMaxResults($nbResult)            
            ;
        }
        
        return $requete->getQuery()->execute();
        
    }

    public function findParcours($varAnnee,$varIdMois,$nbResult, $varIdService, $varIdEntreprise, $varIdSalarie)
    {
        $requete = $this
            ->createQueryBuilder('p')
            ->leftJoin(Salarie::class, 'salarie', 'WITH', 'salarie.id = p.idSalarie')
            ->leftJoin(Service::class, 'service', 'WITH', 'service.id = salarie.idService')
            ->leftJoin(Entreprise::class, 'entreprise', 'WITH', 'entreprise.id = salarie.idEntreprise')
            ->where('p.validation = 1')

            
            ->groupBy('p.id')
            ;

            if(!is_null($varAnnee)){
                $requete           
                ->andWhere('p.annee = :an')                            
                ->setParameter('an', $varAnnee) 
                ->orderBy('p.annee')
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


            if(!is_null($varIdSalarie)){
                $requete           
                ->andWhere('salarie.id = :idSalarie')                            
                ->setParameter('idSalarie', $varIdSalarie)            
                ;
            }

            if(!is_null($varIdEntreprise)){
                $requete           
                ->andWhere('entreprise.id = :idEntreprise')                            
                ->setParameter('idEntreprise', $varIdEntreprise)            
                ;
            }

            if(!is_null($nbResult)){
                $requete           
                ->setMaxResults($nbResult)            
                ;
            }
            $requete ->orderBy('p.annee DESC, p.idMois, p.idSalarie');
            return $requete->getQuery()->execute();
    }


    public function findWithNbAndValidation($nbResult, $varValidation)
    {
        $requete =  $this->createQueryBuilder('p')
                        ->orderBy('p.annee','DESC')
                        ->orderBy('p.idMois, p.idSalarie')
                        ;
        
        if(!is_null($nbResult)){
            $requete           
            ->setMaxResults($nbResult)            
            ;
        }

        if($varValidation === "Validé"){
            $requete
            ->andWhere('p.validation =  1')
            ;         
        }
        if($varValidation === "Non validé"){
            $requete
            ->andWhere('p.validation =  0')
            ;         
        }
        
        return $requete->getQuery()->execute();
        
    }
}

