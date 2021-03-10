<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ParcoursDateRepository
 *
 * @author christophe
 */
class ParcoursDateRepository extends EntityRepository {
    
    /* POUR UN UTILISATEUR PARTICULIER */
    
    /**
     * cherche pour un salarié
     *  les stats nb km pour une année
     * @param string $ville
     *
     * @return array
     */
    public function statParcoursUserAnNbKm($idUser,$an)
    {
        return $this
            ->createQueryBuilder('pd')
            ->select('SUM(pd.nbKmEffectue) as nbKmEffectue')
            ->leftJoin('pd.idParcours', 'p')
            ->andWhere('p.annee = :an')
            ->andWhere('p.idSalarie = :idsalarie')
            
            ->setParameter('an', $an)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    
    /**
     * cherche pour un salarié
     *  les stats indemnisation pour une année
     * @param string $ville
     *
     * @return array
     */
    public function statParcoursUserAnIndemnisation($idUser,$an)
    {
        return $this
            ->createQueryBuilder('pd')
            ->select('SUM(pd.indemnisation) as nbKmEffectue')
            ->leftJoin('pd.idParcours', 'p')
            ->andWhere('p.annee = :an')
            ->andWhere('p.idSalarie = :idsalarie')
            
            ->setParameter('an', $an)
            ->setParameter('idsalarie', $idUser)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /*
     * Recuperation du nombre de KM et le montant des indemnités
     * pour un deplacement
     */
    public function recupParcoursDate4Parcours($idParcours)
    {
        return $this
            ->createQueryBuilder('pd')
            ->select('SUM(pd.indemnisation) as totIndemnisation,SUM(pd.nbKmEffectue) as nbKmEffectue')            
            ->where('pd.idParcours = :idParcours')            
            ->setParameter('idParcours', $idParcours)
            //->orderBy('a.ville')
            //->setMaxResults($nbResult)
            ->getQuery()
            ->getResult()
            ;
    }
}
