<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function getArticles($page, $nbPerPage)
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
	
	public function getPublishedArticles($page, $nbPerPage)
	{
		$query = $this->createQueryBuilder('a')
			->where('a.status = :status')
			->setParameter('status', 'published')
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
	
	public function getLastArticles()
	{
		$query = $this->createQueryBuilder('a')
			->where('a.status = :status')
			->setParameter('status', 'published')
			->orderBy('a.date_add', 'DESC')
			->getQuery()
			->setMaxResults(3)
			->getResult()
		;
		return $query;
	}
}
