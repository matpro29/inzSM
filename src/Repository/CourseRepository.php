<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findAllQB()
    {
        return $this->createQueryBuilder('c');
    }

    public function findAllBySearchForm($name)
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function findAllByOwnerId($owner_id)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.owner', 'o')
            ->where('o.id = :owner_id')
            ->setParameter('owner_id', $owner_id)
            ->getQuery()
            ->getResult();
    }

    public function findAllByUserId($user_id)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult();
    }
}
