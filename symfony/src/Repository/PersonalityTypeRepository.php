<?php

namespace App\Repository;

use App\Entity\PersonalityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalityType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalityType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalityType[]    findAll()
 * @method PersonalityType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalityTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalityType::class);
    }

    // /**
    //  * @return PersonalityType[] Returns an array of PersonalityType objects
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
    public function findOneBySomeField($value): ?PersonalityType
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
