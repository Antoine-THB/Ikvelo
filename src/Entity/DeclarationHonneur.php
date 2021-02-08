<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeclarationHonneur
 *
 * @ORM\Table(name="declaration_honneur", indexes={@ORM\Index(name="fk_id_ville", columns={"id_ville"}), @ORM\Index(name="fk_id_entreprise", columns={"id_entreprise"}), @ORM\Index(name="fk_id_salarie", columns={"id_salarie"})})
 * @ORM\Entity
 */
class DeclarationHonneur
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
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    private $adresse;

    /**
     * @var float
     *
     * @ORM\Column(name="distance", type="float", precision=10, scale=0, nullable=false)
     */
    private $distance;

    /**
     * @var float
     *
     * @ORM\Column(name="prime", type="float", precision=10, scale=0, nullable=false)
     */
    private $prime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url_geovelo", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $urlGeovelo = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true, options={"default"="1"})
     */
    private $actif = true;

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
     * @var \Entreprise
     *
     * @ORM\ManyToOne(targetEntity="Entreprise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_entreprise", referencedColumnName="id")
     * })
     */
    private $idEntreprise;

    /**
     * @var \Salarie
     *
     * @ORM\ManyToOne(targetEntity="Salarie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_salarie", referencedColumnName="id")
     * })
     */
    private $idSalarie;

    /**
     * @var \Ville
     *
     * @ORM\ManyToOne(targetEntity="Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ville", referencedColumnName="id")
     * })
     */
    private $idVille;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getPrime(): ?float
    {
        return $this->prime;
    }

    public function setPrime(float $prime): self
    {
        $this->prime = $prime;

        return $this;
    }

    public function getUrlGeovelo(): ?string
    {
        return $this->urlGeovelo;
    }

    public function setUrlGeovelo(?string $urlGeovelo): self
    {
        $this->urlGeovelo = $urlGeovelo;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

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

    public function getIdEntreprise(): ?Entreprise
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(?Entreprise $idEntreprise): self
    {
        $this->idEntreprise = $idEntreprise;

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

    public function getIdVille(): ?Ville
    {
        return $this->idVille;
    }

    public function setIdVille(?Ville $idVille): self
    {
        $this->idVille = $idVille;

        return $this;
    }


}
