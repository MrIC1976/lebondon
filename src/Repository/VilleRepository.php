<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Ville>
 *
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    public function add(Ville $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ville $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findCoordonneeByNomVille($value): array
        {
            return $this->createQueryBuilder('v')
                ->andWhere('v.nomVille = :val')
                ->setParameter('val', $value)
                ->orderBy('v.nomVille', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult()

            ;
        }

    public function findIdVilleSelonDistance($minLon, $maxLon, $minLat, $maxLat): array
    {
        return $this->createQueryBuilder('v')
            //->select('v.idVille')
            ->select('v.idVille')
            //->addSelect('annonce')
            ->where('v.longitude BETWEEN :minLon AND :maxLon')
            ->andWhere('v.latitude BETWEEN :minLat AND :maxLat')
            ->setParameter(':minLon', $minLon)
            ->setParameter(':maxLon', $maxLon)
            ->setParameter(':minLat', $minLat)
            ->setParameter(':maxLat', $maxLat)
            ->Join('App\Entity\Annonce', 'annonce', 'WITH', 'annonce.idVille = v.idVille')
            ->getQuery()
            ->getResult();
            ;
    }
    
   /* public function findClostestTo(float $latitude, float $longitude, float $milesLimit = null, int $resultsLimit = 1): ?array
{
    $qb = $this->createQueryBuilder("entity");

    // Find and sort records by their direct distance
    $qb
        ->addSelect("DEGREES(ACOS((SIN(RADIANS(:latitude)) * SIN(RADIANS(entity.latitude))) + (COS(RADIANS(:latitude)) * COS(RADIANS(entity.latitude)) * COS(RADIANS(:longitude - entity.longitude))))) * :radius AS distanceMiles")
        ->addSelect("(distanceMiles * 1.609344) AS distanceKilometres")
        // ->addSelect("(distanceMiles * 0.868976) AS distanceNauticalMiles")
        ->setParameter("latitude", $latitude)
        ->setParameter("longitude", $longitude)
        ->setParameter("radius", (60 * 1.1515))
        ->addOrderBy("distanceMiles", "ASC")
    ;

    // Optional: Clamp results to a direct distance
    if (is_numeric($milesLimit) && $milesLimit > 0.0) {
        $qb
            ->andWhere("distanceMiles < :milesLimit")
            ->setParameter("milesLimit", $milesLimit)
        ;
    }

    // Limit quantity of direct distance results, important to reduce route distance API call count later
    $qb->setMaxResults(max(min($resultsLimit, 50), 1));

    return $qb->getQuery()->getResult();
}*/
    /*
        public function findAllVisibleQuery(AdSearch $search): array
        {
            if ($latitudeVille && $longitudeVille && $distance) {
                return $this->findVisibleQuery()
                    ->select('v')
                    ->addSelect('(6353 * 2 * ASIN(SQRT( POWER(SIN((a.lat - :lat) *  pi()/180 / 2), 2) +COS(a.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((a.lng - :lng) * pi()/180 / 2), 2) ))) AS HIDDEN distance')
                    ->having('distance<=:distance')
                    ->setParameter('lng', $longitudeVille)
                    ->setParameter('lat', $latitudeVille)
                    ->setParameter('distance', $search->getDistance())
                    ->addOrderBy("distance", "ASC")
                    ->getQuery()
                    ->getResult();
            }
    

*/

//    /**
//     * @return Ville[] Returns an array of Ville objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ville
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function obtenirVilleParAnnonce(): array
{
    return $this->createQueryBuilder('v')
    ->groupBy('v.idVille')
        ->getQuery()
        ->getResult()
    ;
}


}

