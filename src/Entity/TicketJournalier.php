<?php

namespace App\Entity;

use App\Repository\TicketJournalierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ticket_journalier", indexes={@ORM\Index(name="fk_id_salarie", columns={"id_salarie"})})
 * @ORM\Entity(repositoryClass="App\Repository\TicketJournalierRepository")
 */
class TicketJournalier
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
    private $date;
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

    public function setIdSalarie(Salarie $id_salarie): self
    {
        $this->id_salarie = $id_salarie;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
