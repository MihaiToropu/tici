<?php

namespace App\Repository;

use App\Entity\Folder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Folder::class);
    }

    public function findAllByCompanyId($companyId)
	{
		return $this->createQueryBuilder('company')
			->andWhere('company.company=:companyId')
			->setParameter('companyId', '21')
			->getQuery()
			->getArrayResult()
			;
	}
    // /**
    //  * @return Folder[] Returns an array of Folder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Folder
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByIdWithRelatedEntities(Folder $folder)
    {
        return $this->createQueryBuilder('f')
            ->select('f', 't', 'v')
            ->where('f.id = :val')
            ->setParameter('val', $folder->getId())
            ->leftJoin('f.tutorials', 't')
            ->leftJoin('t.videos', 'v')
            ->getQuery()->execute()
		;

    }
}
