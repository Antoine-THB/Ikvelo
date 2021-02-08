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
     * @ORM\Column(name="type_config", type="string", length=100, nullable=false)
     */
    private $typeConfig;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeConfig(): ?string
    {
        return $this->typeConfig;
    }

    public function setTypeConfig(string $typeConfig): self
    {
        $this->typeConfig = $typeConfig;

        return $this;
    }


}
