<?php

namespace App\Repository;

use App\Entity\Phrase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Phrase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phrase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phrase[]    findAll()
 * @method Phrase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhraseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Phrase::class);
    }


    public function findByFilters($cId, $pId, $ptId)
    {
        $qb = $this->createQueryBuilder('p');

        if (is_numeric($cId)) {
            $qb->andWhere('p.category = :cId');
            $qb->setParameter('cId', $cId);
        }

        if (is_numeric($pId)) {
            $qb->andWhere('p.phraseTyp = :pId');
            $qb->setParameter('pId', $pId);
        }

        if (is_numeric($ptId)) {
            $qb->andWhere('p.personalityTyp = :ptId');
            $qb->setParameter('ptId', $ptId);
        }


        return $qb->getQuery()->getResult();

    }

    // /**
    //  * @return Phrase[] Returns an array of Phrase objects
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
    public function findOneBySomeField($value): ?Phrase
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
