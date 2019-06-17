<?php

namespace App\Repository;

use App\Entity\PhraseToAlternative;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhraseToAlternative|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhraseToAlternative|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhraseToAlternative[]    findAll()
 * @method PhraseToAlternative[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhraseToAlternativeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhraseToAlternative::class);
    }

    public function findOneByIds($pId, $apId)
    {
        $qb = $this->createQueryBuilder('pta')
        ->andWhere('pta.phrase = :pId')
        ->setParameter('pId', $pId)
        ->andWhere('pta.alternativePhrase = :apId')
        ->setParameter('apId', $apId)
        ->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return PhraseToAlternative[] Returns an array of PhraseToAlternative objects
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
    public function findOneBySomeField($value): ?PhraseToAlternative
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
