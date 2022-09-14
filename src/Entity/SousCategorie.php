<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousCategorie
 *
 * @ORM\Table(name="sous_categorie", indexes={@ORM\Index(name="SOUS_CATEGORIE_CATEGORIE_FK", columns={"ID_CATEGORIE"})})
 * @ORM\Entity(repositoryClass= "App\Repository\SousCategorieRepository")
 */
class SousCategorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_SOUS_CATEGORIE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSousCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_SOUS_CATEGORIE", type="string", length=100, nullable=false)
     */
    private $nomSousCategorie;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CATEGORIE", referencedColumnName="ID_CATEGORIE")
     * })
     */
    private $idCategorie;



    /**
     * Get the value of idSousCategorie
     *
     * @return  int
     */ 
    public function getIdSousCategorie()
    {
        return $this->idSousCategorie;
    }

    /**
     * Set the value of idSousCategorie
     *
     * @param  int  $idSousCategorie
     *
     * @return  self
     */ 
    public function setIdSousCategorie(int $idSousCategorie)
    {
        $this->idSousCategorie = $idSousCategorie;

        return $this;
    }

    /**
     * Get the value of nomSousCategorie
     *
     * @return  string
     */ 
    public function getNomSousCategorie()
    {
        return $this->nomSousCategorie;
    }

    /**
     * Set the value of nomSousCategorie
     *
     * @param  string  $nomSousCategorie
     *
     * @return  self
     */ 
    public function setNomSousCategorie(string $nomSousCategorie)
    {
        $this->nomSousCategorie = $nomSousCategorie;

        return $this;
    }

    public function getIdCategorie(): ?Categorie

    {
        return $this->idCategorie;
    }


    public function setIdCategorie(?Categorie $idCategorie): self

    /**
     * Set the value of idCategorie
     *
     * @param  \Categorie  $idCategorie
     *
     * @return  self
     */ 

    {
        $this->idCategorie = $idCategorie;

        return $this;
    }
}
