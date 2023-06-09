<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\Search;
use App\Entity\Shop;
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
        if($search->getName()){
            $query = $query ->andWhere("s.name LIKE :name")
                            ->setParameter('name', '%'.$search->getName().'%');
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
	public function findRestaurants(Search $search , int $radius = 1000): array {
        
		$latitude = $search->getCoordinates()->getLatitude();
		$longitude = $search->getCoordinates()->getLongitude();
        $limit = 20;

		$results = $this->createQueryBuilder('s')
			->select('s', 'a', 'st_distance_sphere(a.coordinates, POINT(:longitude, :latitude)) as distance')
			->join('s.address', 'a')
			->orderBy('distance')
			->setMaxResults($limit)
			->setParameter('latitude', $latitude)
			->setParameter('longitude', $longitude)
		;

        if (count($search->getCategory()) > 0) {
            $results->join('s.category', 'c');
            $tagConditions = [];
            foreach ($search->getCategory() as $category) {
                if ($category !== null) {
                    $tagConditions[] = 'c.name LIKE :category_'.$category->getId();
                    $results->setParameter('category_'.$category->getId(), '%'.$category->getName().'%');
                }
            }
            // Combine the condition with a 'OR' bewtween them.
            $results->andWhere(implode(' OR ', $tagConditions));
        }

        $results->getQuery()
                ->getResult();

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
