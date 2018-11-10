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
}
