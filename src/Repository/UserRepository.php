<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Users[]    findBySearch(int $offset, string $orderBy, string $sc)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //    /**
    //     * @return Users[] Returns an array of Users objects
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



    /* Get Params and join Tables Users and Registry to search in both
    -> with LIKE and % befor % behind
    -> Offest, ASc or Desc and Limit = 10
     */
    /**
     * @return Users[]
     */
    public function findBySearch(
        int $offset,
        string $orderBy,
        string $sc,
        string $email,
        string $name,
        string $telefon,
        string $postcode,
        string $place,
        int $limit
    ): array {
        $entityManager = $this->getEntityManager();

        /* Search in Table o (Users) when orderBy = email, else search in Table Registry (name,postcode, place, telefon)  */
        $result = $entityManager->getRepository(User::class)->createQueryBuilder('o')
            ->select('o')
            ->where('o.email LIKE :email')
            ->andWhere('o.name LIKE :name')
            ->andWhere('o.telefon LIKE :telefon')
            ->andWhere('o.postcode LIKE :postcode')
            ->andWhere('o.place LIKE :place')
            ->setParameter('email', '%' . $email . '%')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('telefon', '%' . $telefon . '%')
            ->setParameter('postcode', '%' . $postcode . '%')
            ->setParameter('place', '%' . $place . '%')
            ->orderBy("o." . $orderBy, $sc)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $result->getQuery()->getResult();
    }
}
