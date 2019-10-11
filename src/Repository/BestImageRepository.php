<?php

namespace App\Repository;

use App\Entity\BestImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method BestImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BestImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BestImage[]    findAll()
 * @method BestImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BestImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BestImage::class);
    }

    public function getImages($page, $nbPerPage)
	{
		$query = $this->createQueryBuilder('a')
		  ->orderBy('a.date_add', 'DESC')
		  ->getQuery()
		;

		$query
		  ->setFirstResult(($page-1) * $nbPerPage)
		  ->setMaxResults($nbPerPage)
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (n'oubliez pas le use correspondant en début de fichier)
		return new Paginator($query, true);
    }
    
    public function getPublishedImages($number)
	{
		$query = $this->createQueryBuilder('a')
          ->orderBy('a.date_add', 'DESC')
          ->setFirstResult(0)
          ->setMaxResults($number)
		  ->getQuery()
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (n'oubliez pas le use correspondant en début de fichier)
		return $query->getResult();
	}

    // /**
    //  * @return BestImage[] Returns an array of BestImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BestImage
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
