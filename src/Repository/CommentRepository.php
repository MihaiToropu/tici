<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

	/**
	 * @param null|string $word
	 */
    public function searchCommentsQueryBuilder(?string $word): QueryBuilder
	{
		$queryBuilder = $this->createQueryBuilder('comm');

		if ($word) {
			$queryBuilder
				->andWhere('comm.content LIKE :word')
				->setParameter('word', '%'.$word.'%');
		}

		return $queryBuilder
			->orderBy('comm.createdAt', 'DESC');
	}

	public function findCommentsWoParentIdByVideoId($id)
	{
		return $this->createQueryBuilder('f')
			->andWhere('f.video = :id')
			->andWhere('f.parentId is NULL')
			->setParameter('id', $id)
			->orderBy('f.createdAt', 'ASC')
			->getQuery()
			->getArrayResult()
//			->getResult(Query::HYDRATE_ARRAY)
			;

	}

	public function findCommentsWithParrentIdByVideoId($id)
	{
		return $this->createQueryBuilder('f')
			->andWhere('f.video = :id')
			->andWhere('f.parentId < :parentId')
			->setParameter('id', $id)
			->setParameter('parentId', 1000000)
			->orderBy('f.createdAt', 'ASC')
			->getQuery()
			->getArrayResult()
//			->getResult(Query::HYDRATE_ARRAY)
			;

	}

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
