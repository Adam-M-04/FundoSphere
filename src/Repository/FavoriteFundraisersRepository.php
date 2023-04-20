<?php

namespace App\Repository;

use App\Entity\FavoriteFundraisers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriteFundraisers>
 *
 * @method FavoriteFundraisers|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteFundraisers|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteFundraisers[]    findAll()
 * @method FavoriteFundraisers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteFundraisersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteFundraisers::class);
    }

    public function save(FavoriteFundraisers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FavoriteFundraisers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FavoriteFundraisers[] Returns an array of FavoriteFundraisers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavoriteFundraisers
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
