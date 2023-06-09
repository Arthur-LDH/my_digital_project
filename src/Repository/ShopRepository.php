<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\Search;
use App\Entity\Shop;
use App\Entity\FoodCategory;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shop>
 *
 * @method Shop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shop[]    findAll()
 * @method Shop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    public function save(Shop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(Filter $search): ORMQuery
    {
        $query = $this->findVisibleQuery();
        if ($search->getName()) {
            $query = $query->andWhere("s.name LIKE :name")
                ->setParameter('name', '%' . $search->getName() . '%');
        }
        // if($search->getRoles()){
        //     $query = $query ->andWhere('s.roles LIKE :roles')
        //                     ->setParameter('roles', '%'.$search->getRoles().'%');
        // }
        // if($search->getIsVerified()){
        //     $query = $query ->andWhere('s.isVerified = :isverified')
        //                     ->setParameter('isverified', $search->getIsVerified());
        // }
        return $query->getQuery();
    }

    public function findVisibleQuery(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('s');
    }

    /**
	 * Get all shops in a radius around a position
	 * @param Search $search
	 * @param int $radius
	 * @return Shop[]
	 */
	public function findRestaurants(Search $search , int $radius = 2000): array {
        
		$latitude = $search->getCoordinates()->getLatitude();
		$longitude = $search->getCoordinates()->getLongitude();
        $limit = 40;

		$queryBuilder = $this->createQueryBuilder('s')
            ->select('s', 'a', 'st_distance_sphere(a.coordinates, POINT(:longitude, :latitude)) as distance')
            ->join('s.address', 'a')
            ->orderBy('distance')
            ->setMaxResults($limit)
            ->setParameter('latitude', $latitude)
            ->setParameter('longitude', $longitude);

        if (count($search->getCategory()) > 0) {
            $subQueryBuilder = $this->createQueryBuilder('s_sub');
            $subQueryBuilder->select('s_sub.id')
                ->join('s_sub.category', 'c_sub');

            $tagConditions = [];
            $parameters = ['latitude' => $latitude, 'longitude' => $longitude];
            foreach ($search->getCategory() as $category) {
                if ($category !== null) {
                    $parameterName = 'category_'.$category->getId();
                    $parameters[$parameterName] = '%'.$category->getName().'%';
                    $tagConditions[] = 'c_sub.name LIKE :' . $parameterName;
                }
            }

            if (!empty($tagConditions)) {
                // Combine the condition with a 'OR' between them.
                $subQueryBuilder->andWhere(implode(' OR ', $tagConditions));
            }

            $queryBuilder
                ->andWhere($queryBuilder->expr()->in('s.id', $subQueryBuilder->getDQL()));

            $queryBuilder->setParameters($parameters);
        }

        $results = $queryBuilder->getQuery()->getResult();

                dd($results);

		$parsedResult = array();
		foreach ($results as $result) {
			$shop = $result[0];
			$shop->setDistance($result['distance']);
			$parsedResult[] = $shop;
		}
		return $parsedResult;
	}
}
