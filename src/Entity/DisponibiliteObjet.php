<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisponibiliteObjet
 *
 * @ORM\Table(name="disponibilite_objet")

 * @ORM\Entity(repositoryClass= "App\Repository\DisponibiliteObjetRepository")

 */
class DisponibiliteObjet
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_DISPONIBILITE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDisponibilite;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_DISPONIBILITE", type="string", length=15, nullable=false)
     */
    private $nomDisponibilite;

    public function getIdDisponibilite(): ?int
    {
        return $this->idDisponibilite;
    }

    public function getNomDisponibilite(): ?string
    {
        return $this->nomDisponibilite;
    }

    public function setNomDisponibilite(string $nomDisponibilite): self
    {
        $this->nomDisponibilite = $nomDisponibilite;

        return $this;
    }


}
