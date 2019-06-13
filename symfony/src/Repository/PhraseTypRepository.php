<?php

namespace App\Repository;

use App\Entity\PhraseTyp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhraseTyp|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhraseTyp|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhraseTyp[]    findAll()
 * @method PhraseTyp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhraseTypRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhraseTyp::class);
    }

    // /**
    //  * @return PhraseTyp[] Returns an array of PhraseTyp objects
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
    public function findOneBySomeField($value): ?PhraseTyp
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
