<?php

namespace App\Repository;

use App\Entity\Userspermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Userspermission>
 *
 * @method Userspermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userspermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userspermission[]    findAll()
 * @method Userspermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserspermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userspermission::class);
    }

//    /**
//     * @return Userspermission[] Returns an array of Userspermission objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Userspermission
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
