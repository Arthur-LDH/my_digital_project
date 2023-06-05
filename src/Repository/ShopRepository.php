<?php

namespace App\Repository;

use App\Entity\Filter;
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
	 * @param Point $position
	 * @param int $radius
	 * @return Shop[]
	 */
	public function findNearby(Point $position, int $radius = 1000): array {
		$latitude = $position->getLatitude();
		$longitude = $position->getLongitude();
		$limit = 10;
	
		$results =  $this->createQueryBuilder('s')
			->select('s', 'a', 'st_distance_sphere(a.coordinates, POINT(:longitude, :latitude)) as distance')
			// ->andWhere('s.visible = true')
			// ->andWhere('s.active = true')
			->join('s.address', 'a')
			->orderBy('distance')
			->setMaxResults($limit)
			->setParameter('latitude', $latitude)
			->setParameter('longitude', $longitude)
			->getQuery()
			->getResult()
		;
		$parsedResult = array();
		foreach ($results as $result) {
			$shop = $result[0];
			$shop->setDistance($result['distance']);
			$parsedResult[] = $shop;
		}
		return $parsedResult;
	}
}
