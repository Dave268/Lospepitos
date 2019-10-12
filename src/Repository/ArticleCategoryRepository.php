<?php

namespace App\Repository;

use App\Entity\ArticleCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticleCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleCategory[]    findAll()
 * @method ArticleCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticleCategory::class);
    }

    public function getActiveCountry($id, $cat)
	{
        $query = $this->createQueryBuilder('a')
            ->where('c.id = :id')
            ->andWhere('d.status = :status')
            ->andWhere('e.id = :continent')
            ->setParameters(array('id' => $id, 'status' => 'Published', 'continent' => $cat))
		    ->leftJoin('a.continent', 'c')
            ->addSelect('c')
            ->leftJoin('a.articles', 'd')
            ->addSelect('d')
            ->leftJoin('d.categories', 'e')
            ->addSelect('e')
			->orderBy('a.name', 'ASC')
			->getQuery()
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (n'oubliez pas le use correspondant en début de fichier)
		return $query->getResult();
	}
}
