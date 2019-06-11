<?php

namespace App\Repository;

use App\Entity\Personality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Personality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personality[]    findAll()
 * @method Personality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Personality::class);
    }

    // /**
    //  * @return Personality[] Returns an array of Personality objects
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
    public function findOneBySomeField($value): ?Personality
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
