<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParcoursDate
 *
 * @ORM\Table(name="parcours_date", indexes={@ORM\Index(name="fk_parcours", columns={"id_parcours"}), @ORM\Index(name="fk_type_trajet", columns={"id_type_trajet"})})
 * @ORM\Entity
 */
class ParcoursDate
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
     * @ORM\Column(name="jour_label", type="string", length=20, nullable=false)
     */
    private $jourLabel;

    /**
     * @var int
     *
     * @ORM\Column(name="jour_num", type="integer", nullable=false)
     */
    private $jourNum;

    /**
     * @var string|null
     *
     * @ORM\Column(name="utilisation_velo", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $utilisationVelo = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $commentaire = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="nb_km_effectue", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $nbKmEffectue;

    /**
     * @var string
     *
     * @ORM\Column(name="indemnisation", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $indemnisation;

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
     * @var \TypeTrajet
     *
     * @ORM\ManyToOne(targetEntity="TypeTrajet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_trajet", referencedColumnName="id")
     * })
     */
    private $idTypeTrajet;

    /**
     * @var \Parcours
     *
     * @ORM\ManyToOne(targetEntity="Parcours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_parcours", referencedColumnName="id")
     * })
     */
    private $idParcours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourLabel(): ?string
    {
        return $this->jourLabel;
    }

    public function setJourLabel(string $jourLabel): self
    {
        $this->jourLabel = $jourLabel;

        return $this;
    }

    public function getJourNum(): ?int
    {
        return $this->jourNum;
    }

    public function setJourNum(int $jourNum): self
    {
        $this->jourNum = $jourNum;

        return $this;
    }

    public function getUtilisationVelo(): ?string
    {
        return $this->utilisationVelo;
    }

    public function setUtilisationVelo(?string $utilisationVelo): self
    {
        $this->utilisationVelo = $utilisationVelo;

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

    public function getNbKmEffectue(): ?string
    {
        return $this->nbKmEffectue;
    }

    public function setNbKmEffectue(string $nbKmEffectue): self
    {
        $this->nbKmEffectue = $nbKmEffectue;

        return $this;
    }

    public function getIndemnisation(): ?string
    {
        return $this->indemnisation;
    }

    public function setIndemnisation(string $indemnisation): self
    {
        $this->indemnisation = $indemnisation;

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

    public function getIdTypeTrajet(): ?TypeTrajet
    {
        return $this->idTypeTrajet;
    }

    public function setIdTypeTrajet(?TypeTrajet $idTypeTrajet): self
    {
        $this->idTypeTrajet = $idTypeTrajet;

        return $this;
    }

    public function getIdParcours(): ?Parcours
    {
        return $this->idParcours;
    }

    public function setIdParcours(?Parcours $idParcours): self
    {
        $this->idParcours = $idParcours;

        return $this;
    }


}
