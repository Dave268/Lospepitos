<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
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
	
	public function getPublishedArticles($page, $nbPerPage, $category, $type = 'all', $sub = 'all')
	{
		if($type == 'pays'){
			$query = $this->createQueryBuilder('a')
				->where('category.name = :category')
				->andWhere('country.name = :sub')
				->andWhere('a.status = :status')
				->setParameters(array('category' => $category, 'sub' => $sub, 'status' => 'Published'))
				->leftJoin('a.categories', 'category')
            	->addSelect('category')
				->leftJoin('a.country', 'country')
            	->addSelect('country')
				->orderBy('a.date_add', 'DESC')
				->getQuery()
			;
		}
		else if($type == 'sub'){
			$query = $this->createQueryBuilder('a')
				->where('category.name = :category')
				->andWhere('sub.name = :sub')
				->andWhere('a.status = :status')
				->setParameters(array('category' => $category, 'sub' => $sub, 'status' => 'Published'))
				->leftJoin('a.categories', 'category')
            	->addSelect('category')
				->leftJoin('a.subCategories', 'sub')
            	->addSelect('sub')
				->orderBy('a.date_add', 'DESC')
				->getQuery()
			;
		}
		else if($category == 'all'){
			$query = $this->createQueryBuilder('a')
				->where('a.status = :status')
				->setParameters(array('status' => 'Published'))
				->orderBy('a.date_add', 'DESC')
				->getQuery()
			;
		}
		else {
			$query = $this->createQueryBuilder('a')
				->where('category.name = :category')
				->andWhere('a.status = :status')
				->setParameters(array('category' => $category, 'status' => 'Published'))
				->leftJoin('a.categories', 'category')
            	->addSelect('category')
				->orderBy('a.date_add', 'DESC')
				->getQuery()
			;
		}
		

		$query
		  ->setFirstResult(($page-1) * $nbPerPage)
		  ->setMaxResults($nbPerPage)
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (n'oubliez pas le use correspondant en début de fichier)
		return new Paginator($query, true);
	}
	
	public function getLastArticles($category)
	{
		$query = $this->createQueryBuilder('a');
		$query->leftJoin('a.categories', 'cat')
			->addSelect('cat');
		$query->where($query->expr()->in('cat.id', $category))
			->andWhere('a.status = :status')
			->setParameter('status', 'published')
			->orderBy('a.date_add', 'DESC')
			->getQuery()
			->setMaxResults(3)
			->getResult()
		;
		return $query;
	}
    
}
