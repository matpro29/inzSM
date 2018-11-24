<?php

namespace App\Repository;

use App\Entity\Webinar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Webinar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Webinar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Webinar[]    findAll()
 * @method Webinar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebinarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Webinar::class);
    }

    public function findAllByCourseId($courseId)
    {
        return $this->createQueryBuilder('w')
            ->innerJoin('w.course', 'c')
            ->where('c.id = :courseId')
            ->andWhere('w.isActive = false')
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getResult();
    }

    public function findOneByCourseId($courseId)
    {
        return $this->createQueryBuilder('w')
            ->innerJoin('w.course', 'c')
            ->where('c.id = :courseId')
            ->andWhere('w.isActive = true')
            ->setMaxResults(1)
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
