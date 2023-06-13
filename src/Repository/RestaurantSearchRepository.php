<?php

namespace App\Repository;

use App\Entity\RestaurantSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @extends ServiceEntityRepository<RestaurantSearch>
 *
 * @method RestaurantSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantSearch[]    findAll()
 * @method RestaurantSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantSearch::class);
    }

    public function save(RestaurantSearch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RestaurantSearch $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
	 * @return Integer
	 */
    public function findTotalResearchesThisMonth(): int {
        $currentDate = new \DateTime();
        $startDate = new \DateTime($currentDate->format('Y-m-01'));
        $endDate = clone $startDate;
        $endDate->modify('first day of next month');
    
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->select('COUNT(r.id) as totalResearches')
            ->where($queryBuilder->expr()->between('r.created_at', ':startDate', ':endDate'))
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
    
        $result = $queryBuilder->getQuery()->getSingleScalarResult();
    
        return $result;
    }

    /**
	 * @return Array
	 */
    public function findMostFrequentCities(): array {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->select('r.user_city, COUNT(r.id) as cityCount')
            ->groupBy('r.user_city')
            ->orderBy('cityCount', 'DESC');
        
        $result = $queryBuilder->getQuery()->getResult();
        
        return $result;
    }

    /**
	 * @return Array
	 */
    public function findMostFrequentCategories(): array {
        $entityManager = $this->getEntityManager();
    
        $query = $entityManager->createQuery('
            SELECT fc.name AS categoryName, COUNT(rs) AS searchCount
            FROM App\Entity\RestaurantSearch rs
            JOIN rs.category fc
            GROUP BY fc.name
            ORDER BY searchCount DESC
        ');
    
        $results = $query->getResult();
        return $results;
    }

}
