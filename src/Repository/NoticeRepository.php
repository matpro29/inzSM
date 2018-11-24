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

    public function findAllAdmin()
    {
        return $this->createQueryBuilder('n')
            ->where('n.course IS NULL')
            ->andWhere('n.endDate > :nowDate')
            ->setParameter('nowDate', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findAllByUserId($userId)
    {
        return $this->createQueryBuilder('n')
            ->innerJoin('n.course', 'c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :userId')
            ->andWhere('n.endDate > :nowDate')
            ->setParameters([
                'nowDate' => new \DateTime(),
                'userId' => $userId
            ])
            ->getQuery()
            ->getResult();
    }

    public function findAllNewAdminByUserId($userId, UserRepository $userRepository)
    {
        return $this->createQueryBuilder('n')
            ->where('n.course IS NULL')
            ->andWhere('n.startDate > :noticeDate')
            ->setParameter('noticeDate', $userRepository->findOneBy([
                'id' => $userId
            ])
                ->getNoticeDate())
            ->getQuery()
            ->getResult();
    }

    public function findAllNewByUserId($userId)
    {
        return $this->createQueryBuilder('n')
            ->innerJoin('n.course', 'c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :userId')
            ->andWhere('n.startDate > u.noticeDate')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
