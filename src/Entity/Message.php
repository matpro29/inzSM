<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conversation", inversedBy="messages")
     */
    private $conversation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messagesSend")
     */
    private $owner;

    public function getConversation()
    {
        return $this->conversation;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setConversation($conversation): void
    {
        $this->conversation = $conversation;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }
}
