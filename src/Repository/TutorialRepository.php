<?php

namespace App\Repository;

use App\Entity\Tutorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tutorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutorial[]    findAll()
 * @method Tutorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorialRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, Tutorial::class);
	}

	public function findVideoById($id)
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getArrayResult()//			->getResult(Query::HYDRATE_ARRAY)
			;

	}

	public function findTutorialsByFolderId($folderId)
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.Folder=:folderId')
			->setParameter('folderId', '' . $folderId)
			->getQuery()
			->getArrayResult();
	}

	public function searchTutorialsByCategory($category)
	{
		dd($this->createQueryBuilder('t')
			->andWhere('t.Categories=:category')
			->setParameter('category', '' . $category)
			->getQuery());
		return $this->createQueryBuilder('t')
			->andWhere('t.Categories=:category')
			->setParameter('category', '' . $category)
			->getQuery()
			->getResult(Query::HYDRATE_ARRAY);
	}

	public function findTutorialsWoCompany()
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.Folder=:f')
			->getQuery()
			->getArrayResult();
	}

	public function searchTutorialsQueryBuilder(?string $word)
	{
		$queryBuilder = $this->createQueryBuilder('tutorial');

		if ($word) {
			$queryBuilder
				->andWhere('tutorial.title LIKE :word')
				->setParameter('word', '%' . $word . '%');
		}

		return $queryBuilder
			->orderBy('tutorial.title', 'DESC');

	}

//			->getResult(Query::HYDRATE_ARRAY)

	// /**
	//  * @return Tutorial[] Returns an array of Tutorial objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('t.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Tutorial
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
