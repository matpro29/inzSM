<?php

namespace App\Repository;

use App\Entity\Notice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notice[]    findAll()
 * @method Notice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notice::class);
    }

    public function findAllByUserId($user_id)
    {
        return $this->createQueryBuilder('n')
            ->innerJoin('n.course', 'c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult();
    }

    public function findAllByUserIdWithLimit($limit, $user_id)
    {
        return $this->createQueryBuilder('n')
            ->innerJoin('n.course', 'c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :user_id')
            ->setMaxResults($limit)
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult();
    }
}
