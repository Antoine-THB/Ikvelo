<?php

namespace App\Entity;

use App\Repository\RemboursementAbonnementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="remboursement_abonnement ", indexes={@ORM\Index(name="fk_abonnement", columns={"id_abonnement"})})
 * @ORM\Entity(repositoryClass=RemboursementAbonnementRepository::class)
 */
class RemboursementAbonnement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @var \Abonnement
     *
     * @ORM\ManyToOne(targetEntity="Abonnement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_abonnement", referencedColumnName="id")
     * })
     */
    private $idAbonnement;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdAbonnement(): ?Abonnement
    {
        return $this->idAbonnement;
    }

    public function setIdAbonnement(Abonnement $idAbonnement): self
    {
        $this->idAbonnement = $idAbonnement;

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
}
