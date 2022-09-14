<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departement
 *
 * @ORM\Table(name="departement")
 * @ORM\Entity(repositoryClass= "App\Repository\DepartementRepository")
 */
class Departement
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_DEPARTEMENT", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDepartement;

        /**
     * @var int
     *
     * @ORM\Column(name="CODE_DEPARTEMENT", type="integer", nullable=false)
     */
    private $codeDepartement;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_DEPARTEMENT", type="string", length=100, nullable=false)
     */
    private $nomDepartement;

        /**
     * @var string
     *
     * @ORM\Column(name="SLUG_DEPARTEMENT", type="string", length=150, nullable=false)
     */
    private $slugDepartement;
    


    public function getIdDepartement(): ?int
    {
        return $this->idDepartement;
    }

    public function getNomDepartement(): ?string
    {
        return $this->nomDepartement;
    }

    public function setNomDepartement(string $nomDepartement): self
    {
        $this->nomDepartement = $nomDepartement;

        return $this;
    }



    /**
     * Get the value of codeDepartement
     *
     * @return  int
     */ 
    public function getCodeDepartement()
    {
        return $this->codeDepartement;
    }

    /**
     * Set the value of codeDepartement
     *
     * @param  int  $codeDepartement
     *
     * @return  self
     */ 
    public function setCodeDepartement(int $codeDepartement)
    {
        $this->codeDepartement = $codeDepartement;

        return $this;
    }

    /**
     * Get the value of slugDepartement
     *
     * @return  string
     */ 
    public function getSlugDepartement()
    {
        return $this->slugDepartement;
    }

    /**
     * Set the value of slugDepartement
     *
     * @param  string  $slugDepartement
     *
     * @return  self
     */ 
    public function setSlugDepartement(string $slugDepartement)
    {
        $this->slugDepartement = $slugDepartement;

        return $this;
    }
}
