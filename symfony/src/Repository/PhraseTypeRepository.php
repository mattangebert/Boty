<?php

namespace App\Repository;

use App\Entity\PhraseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhraseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhraseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhraseType[]    findAll()
 * @method PhraseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhraseTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhraseType::class);
    }

    // /**
    //  * @return PhraseType[] Returns an array of PhraseType objects
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
    public function findOneBySomeField($value): ?PhraseType
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
