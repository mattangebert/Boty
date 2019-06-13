<?php

namespace App\Repository;

use App\Entity\PersonalityTyp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalityTyp|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalityTyp|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalityTyp[]    findAll()
 * @method PersonalityTyp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalityTypRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalityTyp::class);
    }

    // /**
    //  * @return PersonalityTyp[] Returns an array of PersonalityTyp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonalityTyp
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
