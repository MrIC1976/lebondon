<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Annonce
 *
 * @ORM\Table(name="annonce", indexes={@ORM\Index(name="ANNONCE_ETAT_OBJET_FK", columns={"ID_ETAT"}), @ORM\Index(name="ANNONCE_UTILISATEUR0_FK", columns={"ID_UTILISATEUR"}), @ORM\Index(name="ANNONCE_SOUS_CATEGORIE1_FK", columns={"ID_SOUS_CATEGORIE"}), @ORM\Index(name="ANNONCE_VILLE2_FK", columns={"ID_VILLE"})})
 * @ORM\Entity(repositoryClass= "App\Repository\AnnonceRepository")
 */
class Annonce
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ANNONCE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAnnonce;

    /**
     * @var string
     *
     * @ORM\Column(name="TITRE_ANNONCE", type="string", length=150, nullable=false)
     */
    private $titreAnnonce;

    /**
     * @var string
     *
     * @ORM\Column(name="SLUG_ANNONCE", type="string", length=150, nullable=false)
     */
    private $slugAnnonce;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPTION_ANNONCE", type="string", length=1500, nullable=false)
     */
    private $descriptionAnnonce;

    /**
     * @var string
     *
     * @ORM\Column(name="ADRESSE", type="string", length=150, nullable=false)
     */
    private $adresse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_CREATION", type="datetime", nullable=false)
     */
    private $dateCreationAnnonce;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_UTILISATEUR", referencedColumnName="ID_UTILISATEUR")
     * })
     */
    private $idUtilisateur;

    /**
     * @var \SousCategorie
     *
     * @ORM\ManyToOne(targetEntity="SousCategorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SOUS_CATEGORIE", referencedColumnName="ID_SOUS_CATEGORIE")
     * })
     */

    private $idSousCategorie;

    /**
     * @var \Ville
     *
     * @ORM\ManyToOne(targetEntity="Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_VILLE", referencedColumnName="ID_VILLE")
     * })
     */
    private $idVille;

    /**
     * @var \EtatObjet
     *
     * @ORM\ManyToOne(targetEntity="EtatObjet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_ETAT", referencedColumnName="ID_ETAT")
     * })
     */
    private $idEtat;



    public function getIdAnnonce(): ?int
    {
        return $this->idAnnonce;
    }

    public function getTitreAnnonce(): ?string
    {
        return $this->titreAnnonce;
    }

    public function setTitreAnnonce(string $titreAnnonce): self
    {
        $this->titreAnnonce = $titreAnnonce;

        return $this;
    }

    public function getSlugAnnonce(): ?string
    {
        return $this->slugAnnonce;
    }

    public function setSlugAnnonce(string $slugAnnonce): self
    {
        $this->slugAnnonce = $slugAnnonce;

        return $this;
    }

    public function getDescriptionAnnonce(): ?string
    {
        return $this->descriptionAnnonce;
    }

    public function setDescriptionAnnonce(string $descriptionAnnonce): self
    {
        $this->descriptionAnnonce = $descriptionAnnonce;

        return $this;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreationAnnonce;
    }

    public function setDateCreation(\DateTimeInterface $dateCreationAnnonce): self
    {
        $this->dateCreationAnnonce = $dateCreationAnnonce;

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

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }
    /**
     * Get the value of idEtat
     *
     * @return  \EtatObjet
     */ 
    public function getIdEtat()
    {
        return $this->idEtat;
    }

    /**
     * Set the value of idEtat
     *
     * @param  \EtatObjet  $idEtat
     *
     * @return  self
     */ 
    public function setIdEtat(?EtatObjet $idEtat)
    {
        $this->idEtat = $idEtat;

        return $this;
    }



    
    public function addIdCategorie(SousCategorie $idSousCategorie): self
    {
        if (!$this->idSousCategorie->contains($idSousCategorie)) {
            $this->idSousCategorie[] = $idSousCategorie;
            $idSousCategorie->addIdAnnonce($this);
        }

        return $this;
    }

    public function removeIdSsousCategorie(SousCategorie $idSousCategorie): self
    {
        if ($this->idSousCategorie->removeElement($idSousCategorie)) {
            $idSousCategorie->removeIdAnnonce($this);
        }

        return $this;
    }


    /**
     * Get the value of idSousCategorie
     *
     * @return  \SousCategorie
     */ 
    public function getIdSousCategorie()
    {
        return $this->idSousCategorie;
    }

    /**
     * Set the value of idSousCategorie
     *
     * @param  \SousCategorie  $idSousCategorie
     *
     * @return  self
     */ 
    public function setIdSousCategorie(?SousCategorie $idSousCategorie)
    {
        $this->idSousCategorie = $idSousCategorie;

        return $this;
    }

    public function getDateCreationAnnonce(): ?\DateTimeInterface
    {
        return $this->dateCreationAnnonce;
    }

    public function setDateCreationAnnonce(\DateTimeInterface $dateCreationAnnonce): self
    {
        $this->dateCreationAnnonce = $dateCreationAnnonce;

        return $this;
    }
    public function __toString()
    {
        return $this->idAnnonce;
    }
}
