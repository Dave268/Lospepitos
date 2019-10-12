<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
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
    /*
    public function findOneBySomeField($value): ?Country
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
