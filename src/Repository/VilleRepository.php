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
class VilleRepository extends EntityRepository {
    //put your code here
    
    /**
     * cherche les villes contenant
     * @param string $ville
     *
     * @return array
     */
    public function findVilleLike($ville,$nbResult)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.ville LIKE :ville')
            ->setParameter( 'ville', "%$ville%")
            ->orderBy('a.numDepartement,a.ville')
            //->orderBy('a.ville')
            ->setMaxResults($nbResult)
            ->getQuery()
            ->execute()
            ;
    }



}
