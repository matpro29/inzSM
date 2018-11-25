<?php

namespace App\Repository;

use App\Entity\UserConversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserConversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserConversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserConversation[]    findAll()
 * @method UserConversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConversationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserConversation::class);
    }

    public function findOneByConversationIdUserId($conversationId, $userId)
    {
        return $this->createQueryBuilder('uc')
            ->innerJoin('uc.conversation', 'c')
            ->innerJoin('uc.user', 'u')
            ->where('c.id = :conversationId')
            ->andWhere('u.id = :userId')
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
