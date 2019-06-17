<?php

namespace App\Repository;

use App\Entity\PhraseToReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhraseToReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhraseToReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhraseToReply[]    findAll()
 * @method PhraseToReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhraseToReplyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhraseToReply::class);
    }

    public function findOneByIds($pId, $rpId)
    {
        return $this->createQueryBuilder('ptr')
            ->andWhere('ptr.phrase = :pId')
            ->setParameter('pId', $pId)
            ->andWhere('ptr.replyPhrase = :rpId')
            ->setParameter('rpId', $rpId)
            ->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return PhraseToReply[] Returns an array of PhraseToReply objects
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
    public function findOneBySomeField($value): ?PhraseToReply
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
