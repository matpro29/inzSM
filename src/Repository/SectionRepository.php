<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function findAllByUserIdQB($userId)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.grades', 'usg')
            ->leftJoin('usg.grade', 'g')
            ->innerJoin('s.course', 'c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :userId')
            ->andWhere('g.id IS NULL')
            ->setParameter('userId', $userId);
    }
}
