<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ville
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity
 */
class Ville
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
     * @var string|null
     *
     * @ORM\Column(name="num_departement", type="string", length=3, nullable=true, options={"default"="NULL"})
     */
    private $numDepartement = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=100, nullable=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=false)
     */
    private $codePostal;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumDepartement(): ?string
    {
        return $this->numDepartement;
    }

    public function setNumDepartement(?string $numDepartement): self
    {
        $this->numDepartement = $numDepartement;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

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

    public function __toString()
    {
        return $this->ville;
    }

}
