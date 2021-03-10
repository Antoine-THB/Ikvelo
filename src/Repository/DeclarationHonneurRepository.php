<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VilleRepository
 *
 * @author christophe
 */
class DeclarationHonneurRepository extends EntityRepository {
    //put your code here
    
    /**
     * cherche les villes contenant
     * @param string $ville
     *
     * @return array
     */
    public function find4SalarieActif($salarie)
    {
        return $this
            ->createQueryBuilder('d')
            ->where('d.idSalarie = :salarie')
            ->andWhere('d.actif = true')
            ->setParameter( 'salarie', $salarie)
            ->getQuery()
            ->execute()
            ;
    }



}
