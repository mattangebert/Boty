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

    public function findAllRepliesByFilters($phraseId, $cId, $pId, $ptId)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('r');
        $qb->andWhere('p.id = :phraseId');
        $qb->setParameter('phraseId', $phraseId);
        $qb->innerJoin('App:phraseToReply', 'ptr', 'WITH', 'ptr.phrase = p.id');
        $qb->innerJoin('App:phrase', 'r', 'WITH', 'ptr.replyPhrase = r.id');
        $qb = $this->addFilters($qb, $cId, $pId, $ptId, 'r');

        return $qb->getQuery()->getResult();
    }

    private function addFilters(QueryBuilder $qb, $cId, $pId, $ptId, $alias = 'p')
    {
        if (is_numeric($cId)) {
            $cId = array($cId);
        }

        if (is_numeric($pId)) {
            $pId = array($pId);
        }

        if (is_numeric($ptId)) {
            $ptId = array($ptId);
        }

        if (!empty($cId)) {
            $qb->andWhere($qb->expr()->in($alias. '.category', $cId));
        }

        if (!empty($pId)) {
            $qb->andWhere($qb->expr()->in($alias. '.phraseTyp', $pId));
        }

        if (!empty($ptId)) {
            $qb->andWhere($qb->expr()->in($alias. '.personalityTyp', $ptId));
        }

        return $qb;
    }
}
