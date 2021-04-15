<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="fk_id_salarie", columns={"id_salarie"})})
 * @ORM\Entity(repositoryClass="App\Repository\AbonnementRepository")
 */
class Abonnement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $justificatif;

    /**
     * @var \Salarie
     *
     * @ORM\ManyToOne(targetEntity="Salarie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_salarie", referencedColumnName="id")
     * })
     */
    private $id_salarie;

    /**
     * @ORM\Column(type="date")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;
    
    /**
     * @ORM\Column(type="float")
     */
    private $indemnisation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validation="0";


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJustificatif(): ?string
    {
        return $this->justificatif;
    }

    public function setJustificatif(string $justificatif): self
    {
        $this->justificatif = $justificatif;

        return $this;
    }

    public function getIdSalarie(): ?Salarie
    {
        return $this->id_salarie;
    }

    public function setIdSalarie(int $id_salarie): self
    {
        $this->id_salarie = $id_salarie;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }


    /**
     * Get the value of indemnisation
     */ 
    public function getIndemnisation()
    {
        return $this->indemnisation;
    }

    /**
     * Set the value of indemnisation
     *
     * @return  self
     */ 
    public function setIndemnisation($indemnisation)
    {
        $this->indemnisation = $indemnisation;

        return $this;
    }

    /**
     * Get the value of validation
     */ 
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set the value of validation
     *
     * @return  self
     */ 
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }
}
