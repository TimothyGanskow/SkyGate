<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Users[]    findBySearch(int $offset, string $orderBy, string $sc)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
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


    /* Get Params and verify if empty
    -> then verify by Regex */
    public function Validate(
        string $email,
        string $name,
        string $telefon,
        string $postcode,
        string $place,
        string $passwort,
        string $terms,
        bool $is_new
    ): array {
        $errors = [];
        if (!$email && $is_new === true) {
            $errors[] = "email is required";
        }
        if (!$name && $is_new === true) {
            $errors[] = "name is required";
        }
        if (!$postcode && $is_new === true) {
            $errors[] = "postcode required";
        }
        if (!$place && $is_new === true) {
            $errors[] = "place is required";
        }
        if (!$telefon && $is_new === true) {
            $errors[] = "telephoneNumber is required";
        }
        if (!$passwort && $is_new === true) {
            $errors[] = "passwort is required";
        }
        if (!$terms && $is_new === true) {
            $errors[] = "terms is required";
        }

        /* check the right type */
        if ($email && $email !== "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors[] = "email must be an actuall email";
            }
        }
        if ($name && $name !== "") {
            if (filter_var(
                $name,
                FILTER_VALIDATE_REGEXP,
                array("options" => array("regexp" => "/^[a-zA-ZäöüÄÖÜ ._-]+(?:[-'\s][a-zA-ZäöüÄÖÜ ._-]+)*$/"))
            ) === false) {
                $errors[] = "name cannot contain any special characters or numbers, except - and space";
            }
        }
        if ($postcode && $postcode !== "") {
            if (filter_var(
                $postcode,
                FILTER_VALIDATE_REGEXP,
                array("options" => array("regexp" => "/^[a-zA-Z0-9]+(?:[-'\\s][a-zA-Z0-9]+)*$/"))
            ) === false) {
                $errors[] = "postcode cannot contain any special characters, except -";
            }
        }
        if ($place && $place !== "") {
            if (filter_var(
                $place,
                FILTER_VALIDATE_REGEXP,
                array("options" => array("regexp" => "/^[a-zA-ZäöüÄÖÜ ._-]+(?:[-'\s][a-zA-ZäöüÄÖÜ ._-]+)*$/"))
            ) === false) {
                $errors[] = "place cannot contain any special characters, except - and space";
            }
        }
        if ($telefon && $telefon !== "") {
            if (filter_var(
                $telefon,
                FILTER_VALIDATE_REGEXP,
                array("options" => array("regexp" => "/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{2,6}$/"))
            ) === false) {
                $errors[] = "The phone number cannot contain any special characters, except -,+ or space";
            }
        }

        return $errors;
    }



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
        $orderBy == "email" ? $selectVal = "o." :   $selectVal = "r.";
        $result = $entityManager->getRepository(Users::class)->createQueryBuilder('o')
            ->select('o')
            ->innerJoin('o.registry', 'r')
            ->where('o.email LIKE :email')
            ->andWhere('r.name LIKE :name')
            ->andWhere('r.telefon LIKE :telefon')
            ->andWhere('r.postcode LIKE :postcode')
            ->andWhere('r.place LIKE :place')
            ->setParameter('email', '%' . $email . '%')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('telefon', '%' . $telefon . '%')
            ->setParameter('postcode', '%' . $postcode . '%')
            ->setParameter('place', '%' . $place . '%')
            ->orderBy($selectVal . $orderBy, $sc)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $result->getQuery()->getResult();
    }


    public function sendMail($sendTo, $mailToken)
    {

        $text = <<<Body
        Please click this email to confirm your email: http://localhost:8000/verify/$mailToken;
        Body;

        $email = (new Email())->from($_ENV["MAIL_USERNAME"])->to($sendTo)->subject("Please verify your email")->text($text);
        $dsn = "smtp://" . $_ENV["MAIL_USERNAME"] . ":*dipolmat.insure6570!@" . $_ENV["MAIL_HOST"];

        /* $dsn = "smtp://service@diplomat.insure:*dipolmat.insure6570!@smtp.strato.de:587"; */

        $transporter = Transport::fromDsn($dsn);

        $mailer = new Mailer($transporter);
        $mailer->send($email);
    }
}
