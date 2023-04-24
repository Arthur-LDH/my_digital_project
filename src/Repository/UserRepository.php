<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
    * @return Query
    */
   public function findAllVisibleQuery(Filter $search): ORMQuery
   {
        $query = $this->findVisibleQuery();
        if($search->getName()){
            $query = $query ->andWhere("u.login LIKE :name OR u.email LIKE :name")
                            ->setParameter('name', '%'.$search->getName().'%');

        }
        if($search->getRoles()){
            $query = $query ->andWhere('u.roles LIKE :roles')
                            ->setParameter('roles', '%'.$search->getRoles().'%');
        }
        if($search->getIsVerified()){
            $query = $query ->andWhere('u.isVerified = :isverified')
                            ->setParameter('isverified', $search->getIsVerified());
        }
        return $query->getQuery();
   }

    public function findVisibleQuery(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('u');
    }
}
