<?php

namespace App\Repository;

use App\Entity\UserCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCourse[]    findAll()
 * @method UserCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCourseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserCourse::class);
    }

    public function findAllByCourseId($courseId)
    {
        return $this->createQueryBuilder('uc')
            ->innerJoin('uc.course', 'c')
            ->where('c.id = :courseId')
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getResult();
    }

    public function findOneByCourseIdUserId($courseId, $userId)
    {
        return $this->createQueryBuilder('uc')
            ->innerJoin('uc.course', 'c')
            ->innerJoin('uc.user', 'u')
            ->andWhere('c.id = :courseId')
            ->andWhere('u.id = :userId')
            ->setMaxResults(1)
            ->setParameters([
                'courseId' => $courseId,
                'userId' => $userId
            ])
            ->getQuery()
            ->getResult();
    }
}
