<?php

namespace App\Entity;

use App\Entity\Annonce;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image", indexes={@ORM\Index(name="IMAGE_ANNONCE_FK", columns={"ID_ANNONCE"})})
 * @ORM\Entity(repositoryClass= "App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_IMAGE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idImage;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_IMAGE", type="string", length=150, nullable=false)
     */
    private $nomImage;

    /**
     * @var \Annonce
     *
     * @ORM\ManyToOne(targetEntity="Annonce")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_ANNONCE", referencedColumnName="ID_ANNONCE")
     * })
     */
    private $idAnnonce;



    /**
     * Get the value of idImage
     *
     * @return  int
     */ 
    public function getIdImage(): ?int
    {
        return $this->idImage;
    }

    /**
     * Set the value of idImage
     *
     * @param  int  $idImage
     *
     * @return  self
     */ 
    public function setIdImage(int $idImage)
    {
        $this->idImage = $idImage;

        return $this;
    }

    /**
     * Get the value of nomImage
     *
     * @return  string
     */ 
    public function getNomImage(): ?string
    {
        return $this->nomImage;
    }

    /**
     * Set the value of nomImage
     *
     * @param  string  $nomImage
     *
     * @return  self
     */ 
    public function setNomImage(string $nomImage): self
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    /**
     * Get the value of idAnnonce
     *
     * @return  \Annonce
     */ 
    public function getIdAnnonce(): ?Annonce
    {
        return $this->idAnnonce;
    }

    /**
     * Set the value of idAnnonce
     *
     * @param  \Annonce  $idAnnonce
     *
     * @return  self
     */ 
    public function setIdAnnonce(?Annonce $idAnnonce): self
    {
        $this->idAnnonce = $idAnnonce;

        return $this;
    }
}
