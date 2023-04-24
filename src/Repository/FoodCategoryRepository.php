<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\FoodCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;

/**
 * @extends ServiceEntityRepository<FoodCategory>
 *
 * @method FoodCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodCategory[]    findAll()
 * @method FoodCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodCategory::class);
    }

    public function save(FoodCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FoodCategory $entity, bool $flush = false): void
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
            $query = $query ->andWhere("c.name LIKE :name")
                            ->setParameter('name', '%'.$search->getName().'%');

        }
        return $query->getQuery();
   }

    public function findVisibleQuery(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('c');
    }
}
