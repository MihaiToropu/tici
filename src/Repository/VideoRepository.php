<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Video::class);
    }

    /**
     * @return Video[]
    */

	public function findVideoById($id)
	{
		return $this->createQueryBuilder('f')
			->andWhere('f.id = :id')
			->setParameter('id',''.$id)
			->getQuery()
//			->getResult()
			->getResult(Query::HYDRATE_ARRAY)
		;

	}

    public function findByPublishedAtAndAscOrder()
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.publishedAt IS NOT NULL')
            ->orderBy('v.publishedAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public static function existingCommentsCriteria()
	{
		return Criteria::create()
			->andWhere(Criteria::expr()->eq('isDeleted', false))
			->orderBy(['createdAt' => 'DESC'])
		;
	}

    /*
    public function findOneBySomeField($value): ?Video
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
