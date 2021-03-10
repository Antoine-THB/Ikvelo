<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeConfig
 *
 * @ORM\Table(name="type_config")
 * @ORM\Entity
 */
class TypeConfig
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type_config", type="string", length=100, nullable=false)
     */
    private $typeConfig;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeConfig
     *
     * @param string $typeConfig
     *
     * @return TypeConfig
     */
    public function setLibelle($typeConfig)
    {
        $this->typeConfig = $typeConfig;

        return $this;
    }

    /**
     * Get typeConfig
     *
     * @return string
     */
    public function getTypeConfig()
    {
        return $this->typeConfig;
    }
    
    public function __toString()
    {
        return $this->typeConfig;
    }
}