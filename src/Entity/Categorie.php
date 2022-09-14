<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass= "App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_CATEGORIE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_CATEGORIE", type="string", length=150, nullable=false)
     */
    private $nomCategorie;



    /**
     * Get the value of idCategorie
     *
     * @return  int
     */ 
    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    /**
     * Set the value of idCategorie
     *
     * @param  int  $idCategorie
     *
     * @return  self
     */ 
    public function setIdCategorie(int $idCategorie)
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    /**
     * Get the value of nomCategorie
     *
     * @return  string
     */ 
    public function getNomCategorie()
    {
        return $this->nomCategorie;
    }

    /**
     * Set the value of nomCategorie
     *
     * @param  string  $nomCategorie
     *
     * @return  self
     */ 
    public function setNomCategorie(string $nomCategorie)
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }
}
