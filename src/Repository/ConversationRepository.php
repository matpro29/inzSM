<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findAllByUserId($id)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.users', 'uc')
            ->innerJoin('uc.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
