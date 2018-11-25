<?php

namespace App\Repository;

use App\Entity\UserCourseGrade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserCourseGrade|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCourseGrade|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCourseGrade[]    findAll()
 * @method UserCourseGrade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCourseGradeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserCourseGrade::class);
    }

    public function findAllByCourseId($courseId)
    {
        return $this->createQueryBuilder('ucg')
            ->innerJoin('ucg.course', 'c')
            ->where('c.id = :courseId')
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getResult();
    }

    public function findOneByCourseIdUserId($courseId, $userId)
    {
        return $this->createQueryBuilder('ucg')
            ->innerJoin('ucg.course', 'c')
            ->innerJoin('ucg.user', 'u')
            ->where('c.id = :courseId')
            ->andWhere('u.id = :userId')
            ->setMaxResults(1)
            ->setParameters([
                'courseId' => $courseId,
                'userId' => $userId
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
