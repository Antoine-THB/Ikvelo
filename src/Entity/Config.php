<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 * 
 * @ORM\Table(name="config", indexes={@ORM\Index(name="fk_id_type_config", columns={"id_type_config"})})
 * @ORM\Entity
 */
class Config
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
     * @ORM\Column(name="libelle", type="string", length=100, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;
    
    /**
     * @var string
     *
     * @ORM\Column(name="value_num", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $valueNum;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif;
    
    /**
     * @var TypeConfig 
     *
     * @ORM\ManyToOne(targetEntity="TypeConfig")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_config", referencedColumnName="id", nullable=false)
     * })
     */
    private $idTypeConfig;



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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Config
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Config
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Set valueNum
     *
     * @param string $value
     *
     * @return Config
     */
    public function setValueNum($valueNum)
    {
        $this->valueNum = $valueNum;

        return $this;
    }

    /**
     * Get valueNum
     *
     * @return string
     */
    public function getValueNum()
    {
        return $this->valueNum;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Config
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set actif
     *
     * @param boolean $actif
     *
     * @return Config
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean
     */
    public function getActif()
    {
        return $this->actif;
    }
    
    /**
     * Set idTypeConfig
     *
     * @param \src\Entity\TypeConfig $idTypeConfig
     *
     * @return Config
     */
    public function setIdTypeConfig(TypeConfig $idTypeConfig = null)
    {
        $this->idTypeConfig = $idTypeConfig;

        return $this;
    }

    /**
     * Get idTypeConfig
     *
     * @return \src\Entity\TypeConfig 
     */
    public function getIdTypeConfig()
    {
        return $this->idTypeConfig;
    }
}
