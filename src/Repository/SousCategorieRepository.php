<?php

namespace App\Repository;


use App\Entity\SousCategorie;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SousCategorie>
 *
 * @method SousCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousCategorie[]    findAll()
 * @method SousCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousCategorie::class);
    }

    public function add(SousCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SousCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderByAsc()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.nomSousCategorie', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }




    public function findByCategorieOrderedByAscName(Categorie $category): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.category = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('c.nomSousCategorie', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return SousCategorie[] Returns an array of SousCategorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SousCategorie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function affichageSousCategorieSelonCategorie($nomCategorie): array
{
    return $this->createQueryBuilder('s')
        ->addSelect('categorie.nomCategorie')
        ->andWhere('categorie.nomCategorie = :nomCategorie')
        ->setParameter('nomCategorie', $nomCategorie)
        ->Join('App\Entity\Categorie', 'categorie', 'WITH', 'categorie.idCategorie = s.idCategorie')
        ->getQuery()
        ->getResult()
    ;
}


}
