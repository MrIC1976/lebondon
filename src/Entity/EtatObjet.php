<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * EtatObjet
 *
 * @ORM\Table(name="etat_objet")
 * @ORM\Entity(repositoryClass= "App\Repository\EtatObjetRepository")
 */
class EtatObjet
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ETAT", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEtat;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_ETAT", type="string", length=20, nullable=false)
     */
    private $nomEtat;



    public function getIdEtat(): ?int
    {
        return $this->idEtat;
    }

    public function getNomEtat(): ?string
    {
        return $this->nomEtat;
    }

    public function setNomEtat(?string $nomEtat): self
    {
        $this->nomEtat = $nomEtat;

        return $this;
    }



}
