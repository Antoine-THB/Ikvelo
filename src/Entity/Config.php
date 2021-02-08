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
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
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
     * @var string|null
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $value = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="value_num", type="decimal", precision=10, scale=2, nullable=true, options={"default"="NULL"})
     */
    private $valueNum = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif = '0';

    /**
     * @var \TypeConfig
     *
     * @ORM\ManyToOne(targetEntity="TypeConfig")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_config", referencedColumnName="id")
     * })
     */
    private $idTypeConfig;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValueNum(): ?string
    {
        return $this->valueNum;
    }

    public function setValueNum(?string $valueNum): self
    {
        $this->valueNum = $valueNum;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getIdTypeConfig(): ?TypeConfig
    {
        return $this->idTypeConfig;
    }

    public function setIdTypeConfig(?TypeConfig $idTypeConfig): self
    {
        $this->idTypeConfig = $idTypeConfig;

        return $this;
    }


}
