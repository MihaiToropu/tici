<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, User::class);
	}

	/**
	 * @return User[]
	 */
	public function findAllFirstNamesInOrder()
	{
		return $this->createQueryBuilder('q')
			->orderBy('q.firstName', 'ASC')
			->getQuery()
			->execute();
	}

	/**
	 * @return User[]
	 */
	public function findAllUsersEmail()
	{
		return $this->createQueryBuilder('q')
			->orderBy('q.email', 'ASC')
			->getQuery()
			->execute();
	}

	/**
	 * @return User[]
	 */
	public function findAllUsersEmailMatching(string $query, int $limit = 3)
	{
		return $this->createQueryBuilder('q')
			->andWhere('q.email LIKE :query')
			->setParameter('query', '%' . $query . '%')
			->orderBy('q.email', 'ASC')
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	public function findAllByCompanyId($companyId)
	{
		return $this->createQueryBuilder('user')
			->andWhere('user.company=:companyId')
			->setParameter('companyId', '' . $companyId)
			->getQuery()
			->getArrayResult();
	}
	// /**
	//  * @return User[] Returns an array of User objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('u')
			->andWhere('u.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('u.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?User
	{
		return $this->createQueryBuilder('u')
			->andWhere('u.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
