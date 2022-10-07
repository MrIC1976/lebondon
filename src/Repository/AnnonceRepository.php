<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function add(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Annonce[] Returns an array of Annonce objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Annonce
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    

    public function getAnnonceByImage(): array  
    {
        return $this->createQueryBuilder('a')
            
            ->addSelect('image.nomImage')
            ->addSelect('categorie.nomCategorie')
            ->addSelect('sousCategorie.nomSousCategorie')
            ->addSelect('ville.nomVille')
            ->addSelect('etatObjet.nomEtat')
            ->Join('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            ->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = sousCategorie.idCategorie')
            ->Join('App\Entity\Ville', 'ville', 'WITH', 'a.idVille = ville.idVille')
            ->Join('App\Entity\EtatObjet', 'etatObjet', 'WITH', 'a.idEtat = etatObjet.idEtat')
            ->setMaxResults(8)
            //->setFirstResult(10)
            ->orderBy('a.idAnnonce', 'desc')        
            ->getQuery()
            ->getResult()
        ;
    }

    public function getHuitDernieresAnnonces(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.idAnnonce', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function infoAnnonce2() // En cours de realisation pour page d'acccueil'
    {
        $maxResultPerPage = 8;

        return $this->createQueryBuilder('a')
            
            
            ->addSelect('categorie.nomCategorie')
            ->addSelect('sousCategorie.nomSousCategorie')
            ->addSelect('ville.nomVille')
            ->addSelect('image.nomImage')
            ->Join('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            ->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = sousCategorie.idCategorie')
            ->Join('App\Entity\Ville', 'ville', 'WITH', 'a.idVille = ville.idVille')
            ->Join('App\Entity\EtatObjet', 'etatObjet', 'WITH', 'a.idEtat = etatObjet.idEtat')
            ->Join('App\Entity\Utilisateur', 'utilisateur', 'WITH', 'a.idUtilisateur = utilisateur.idUtilisateur')
            ->orderBy('a.idAnnonce', 'DESC')
            ->setMaxResults($maxResultPerPage)
            ->getQuery()
            ->getResult()
        ;
    }   

    public function infoAnnonceRecherche(): array
    {
        return $this->createQueryBuilder('a')
            ->addSelect('image.nomImage')
            ->addSelect('categorie.nomCategorie')
            ->addSelect('sousCategorie.nomSousCategorie')
            ->addSelect('ville.nomVille')
            ->addSelect('etatObjet.nomEtat')
            ->leftJoin('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            ->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = sousCategorie.idCategorie')
            ->Join('App\Entity\Ville', 'ville', 'WITH', 'a.idVille = ville.idVille')
            ->Join('App\Entity\EtatObjet', 'etatObjet', 'WITH', 'a.idEtat = etatObjet.idEtat')
            ->Join('App\Entity\Utilisateur', 'utilisateur', 'WITH', 'a.idUtilisateur = utilisateur.idUtilisateur')
            ->orderBy('a.idAnnonce', 'DESC')
            //->setMaxResults(12)
            ->getQuery()
            ->getResult()
        ;
    } 


    public function infoAnnonce($value): array
    {
        return $this->createQueryBuilder('a')
            ->addSelect('image.nomImage')
            ->addSelect('categorie.nomCategorie')
            ->addSelect('sousCategorie.nomSousCategorie')
            ->addSelect('ville.nomVille')
            ->addSelect('etatObjet.nomEtat')
            ->andWhere('utilisateur.idUtilisateur = :val')
            ->setParameter('val', $value)
            ->leftJoin('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            ->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = sousCategorie.idCategorie')
            ->Join('App\Entity\Ville', 'ville', 'WITH', 'a.idVille = ville.idVille')
            ->Join('App\Entity\EtatObjet', 'etatObjet', 'WITH', 'a.idEtat = etatObjet.idEtat')
            ->Join('App\Entity\Utilisateur', 'utilisateur', 'WITH', 'a.idUtilisateur = utilisateur.idUtilisateur')
            ->orderBy('a.idAnnonce', 'DESC')
            //->setMaxResults(12)
            ->getQuery()
            ->getResult()
        ;
    }

    public function categorieSelonAnnonce(): array //calcul nombre d'annonce
    {
        return $this->createQueryBuilder('a')
            
            ->addSelect('COUNT(a.idAnnonce) AS tot')
            ->addSelect('categorie.nomCategorie')
            //->andWhere('categorie.nomCategorie = :val')
            //->setParameter('val', $value)
            ->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = sousCategorie.idCategorie')
            ->groupBy('categorie.nomCategorie')
            ->orderBy('tot', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function rechercheAnnonce($mots = null, $categorie = null, $etat = null, $minLon = null, $maxLon = null, $minLat = null, $maxLat = null){
        $query = $this->createQueryBuilder('a');
        
        if($mots != null){
            $query->where('MATCH_AGAINST(a.titreAnnonce, a.descriptionAnnonce) AGAINST 
            (:mots boolean)>0') //MATCH_AGAINST on le retrouve dans config/doctrine.yaml,
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->join('a.idSousCategorie', 's')
                ->join('s.idCategorie', 'c')
                ->andWhere('c.idCategorie = :id')
                ->setParameter('id', $categorie);
        }
        if($etat != null){
            $query->leftJoin('a.idEtat', 'e');
            $query->andWhere('e.idEtat = :id')
                ->setParameter('id', $etat);
        }
        if($minLon != null){
            $query->where('ville.longitude BETWEEN :minLon AND :maxLon')
                    ->andWhere('ville.latitude BETWEEN :minLat AND :maxLat')
                    ->setParameter(':minLon', $minLon)
                    ->setParameter(':maxLon', $maxLon)
                    ->setParameter(':minLat', $minLat)
                    ->setParameter(':maxLat', $maxLat)
                    ->Join('App\Entity\ville', 'ville', 'WITH', 'ville.idVille = a.idVille');
        }
    }

    
    public function findIdVilleSelonDistance2($minLon, $maxLon, $minLat, $maxLat): array
    {
        return $this->createQueryBuilder('a')
            //->select('v.idVille')
            ->select('ville.idVille')
            //->addSelect('annonce')
            ->where('ville.longitude BETWEEN :minLon AND :maxLon')
            ->andWhere('ville.latitude BETWEEN :minLat AND :maxLat')
            ->setParameter(':minLon', $minLon)
            ->setParameter(':maxLon', $maxLon)
            ->setParameter(':minLat', $minLat)
            ->setParameter(':maxLat', $maxLat)
            ->Join('App\Entity\ville', 'ville', 'WITH', 'ville.idVille = a.idVille')
            ->getQuery()
            ->getResult();
            
    }

/* public function rechercheAnnonce($critere): array
    {
        return $this->createQueryBuilder('a')
            
            //->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->leftJoin('a.EtatObjet','etat')
            ->where('etat.nomEtat=:etatNomEtat')
            ->setParameter("etatNomEtat", $critere['idEtat']->getNomEtat())
            ->andWhere("a.idSouscategorie.nomSousCategorie = :nomSousCategorie")
            ->setParameter("nomSousCategorie", $critere['nomSousCategorie']->getnomSousCategorie())
            //->where('reservation.nomReservation'=)
            //->addSelect('sousCategorie.nomSousCategorie')
            //->Join('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            //->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->orderBy('a.dateCreationAnnonce', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }*/
}
