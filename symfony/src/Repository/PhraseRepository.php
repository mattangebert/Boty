<?php

namespace App\Repository;

use App\Entity\Phrase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpParser\Node\Expr\Array_;
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

        $qb = $this->addFilters($qb, $cId, $pId, $ptId);

        return $qb->getQuery()->getResult();
    }

    public function findByFilters2($cId, $pId, $ptId)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.id');

        $qb = $this->addFilters($qb, $cId, $pId, $ptId);


        return $qb->getQuery()->getResult();
    }

    public function findAllWithAlternatives()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('App:phraseToAlternative', 'pta', 'WITH', 'pta.phrase = p.id')
            ->getQuery()->getResult();
    }

    public function findAllWithReplies()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('App:phraseToReply', 'ptr', 'WITH', 'ptr.phrase = p.id')
            ->getQuery()->getResult();
    }

    public function countPhrases($cId = '', $pId = '', $ptId = '')
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('count(p.id)');

        $qb = $this->addFilters($qb, $cId, $pId, $ptId);

        return $qb->getQuery()->getSingleScalarResult();

    }

    public function findRandomPhrase($cId, $pId, $ptId, Array $random_ids, int $max = 1)
    {
        $qb = $this->createQueryBuilder('p');

        $qb = $this->addFilters($qb, $cId, $pId, $ptId);

        $qb->andWhere('p.id IN (:ids)');
        $qb->setParameter('ids', $random_ids);
        $qb->setMaxResults($max);

        return $qb->getQuery()->getResult();
    }

    private function addFilters(QueryBuilder $qb, $cId, $pId, $ptId)
    {
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

        return $qb;
    }
}
