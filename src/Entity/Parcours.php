<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parcours
 *
 * @ORM\Table(name="parcours", indexes={@ORM\Index(name="fk_salarie", columns={"id_salarie"}), @ORM\Index(name="FK_parcours_mois", columns={"id_mois"})})
 * @ORM\Entity(repositoryClass="App\Repository\ParcoursRepository")
 */
class Parcours
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
     * @var int
     *
     * @ORM\Column(name="annee", type="integer", nullable=false)
     */
    private $annee;

    /**
     * @var bool
     *
     * @ORM\Column(name="velo_uniq", type="boolean", nullable=false, options={"comment"="utilisation du velo uniquement (oui/non)"})
     */
    private $veloUniq = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="descript_trajet", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $descriptTrajet = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="distance_base", type="decimal", precision=10, scale=2, nullable=false, options={"comment"="distance travail-domicile realise en velo uniquement"})
     */
    private $distanceBase;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nb_km_effectue", type="decimal", precision=10, scale=2, nullable=true, options={"default"="NULL"})
     */
    private $nbKmEffectue = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="indemnisation", type="decimal", precision=10, scale=2, nullable=true, options={"default"="NULL"})
     */
    private $indemnisation = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date", nullable=false)
     */
    private $dateCreation;

    /**
     * @var bool
     *
     * @ORM\Column(name="cloture", type="boolean", nullable=false)
     */
    private $cloture = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="validation", type="boolean", nullable=false)
     */
    private $validation = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $commentaire = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $created = 'current_timestamp()';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $updated = 'current_timestamp()';

    /**
     * @var \Mois
     *
     * @ORM\ManyToOne(targetEntity="Mois")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_mois", referencedColumnName="id")
     * })
     */
    private $idMois;

    /**
     * @var \Salarie
     *
     * @ORM\ManyToOne(targetEntity="Salarie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_salarie", referencedColumnName="id")
     * })
     */
    private $idSalarie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getVeloUniq(): ?bool
    {
        return $this->veloUniq;
    }

    public function setVeloUniq(bool $veloUniq): self
    {
        $this->veloUniq = $veloUniq;

        return $this;
    }

    public function getDescriptTrajet(): ?string
    {
        return $this->descriptTrajet;
    }

    public function setDescriptTrajet(?string $descriptTrajet): self
    {
        $this->descriptTrajet = $descriptTrajet;

        return $this;
    }

    public function getDistanceBase(): ?string
    {
        return $this->distanceBase;
    }

    public function setDistanceBase(string $distanceBase): self
    {
        $this->distanceBase = $distanceBase;

        return $this;
    }

    public function getNbKmEffectue(): ?string
    {
        return $this->nbKmEffectue;
    }

    public function setNbKmEffectue(?string $nbKmEffectue): self
    {
        $this->nbKmEffectue = $nbKmEffectue;

        return $this;
    }

    public function getIndemnisation(): ?string
    {
        return $this->indemnisation;
    }

    public function setIndemnisation(?string $indemnisation): self
    {
        $this->indemnisation = $indemnisation;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getCloture(): ?bool
    {
        return $this->cloture;
    }

    public function setCloture(bool $cloture): self
    {
        $this->cloture = $cloture;

        return $this;
    }

    public function getValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getIdMois(): ?Mois
    {
        return $this->idMois;
    }

    public function setIdMois(?Mois $idMois): self
    {
        $this->idMois = $idMois;

        return $this;
    }

    public function getIdSalarie(): ?Salarie
    {
        return $this->idSalarie;
    }

    public function setIdSalarie(?Salarie $idSalarie): self
    {
        $this->idSalarie = $idSalarie;

        return $this;
    }

    public function __toString()
    {
        return $this->annee;
    }



}
