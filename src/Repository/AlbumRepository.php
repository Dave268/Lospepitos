<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[]    findAll()
 * @method Album[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Album::class);
    }

    public function getAlbums($page, $nbPerPage)
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

    public function getPublishedAlbums($page, $nbPerPage)
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
	
	public function getLastAlbum()
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
