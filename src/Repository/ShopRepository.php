<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\Shop;
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
}
