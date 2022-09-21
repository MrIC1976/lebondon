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
            //->addSelect('sousCategorie.nomSousCategorie')
            ->Join('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            //->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->setMaxResults(12)
            ->orderBy('a.dateCreationAnnonce', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function infoAnnonce(): array  // En cours de realisation pour page de recherche
    {
        return $this->createQueryBuilder('a')
            
            ->addSelect('image.nomImage')
            ->addSelect('categorie.nomCategorie')
            ->Join('App\Entity\Image', 'image', 'WITH', 'image.idAnnonce = a.idAnnonce')
            ->Join('App\Entity\SousCategorie', 'sousCategorie', 'WITH', 'sousCategorie.idSousCategorie = a.idSousCategorie')
            ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = sousCategorie.idCategorie')
            ->orderBy('a.dateCreationAnnonce', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function categorieSelonAnnonce(): array
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
            ->getQuery()
            ->getResult()
        ;
    }

   /* public function getHuitDernieresAnnonces(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.idAnnonce', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }*/


    

}
