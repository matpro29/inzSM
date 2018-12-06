<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserConversationRepository")
 */
class UserConversation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conversation", inversedBy="users")
     */
    private $conversation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $conversationDate;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="conversations")
     */
    private $user;

    public function getConversation()
    {
        return $this->conversation;
    }

    public function getConversationDate()
    {
        return $this->conversationDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setConversation($conversation): void
    {
        $this->conversation = $conversation;
    }

    public function setConversationDate($conversationDate): void
    {
        $this->conversationDate = $conversationDate;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
