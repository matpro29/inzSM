<?php

namespace App\Repository;

use App\Entity\UserSectionGrade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserSectionGrade|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSectionGrade|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSectionGrade[]    findAll()
 * @method UserSectionGrade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSectionGradeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserSectionGrade::class);
    }
}
