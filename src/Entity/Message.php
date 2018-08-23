<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
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
     * @ORM\OneToMany(targetEntity="App\Entity\UserMessage", mappedBy="message")
     */
    private $receivers;

    public function __construct()
    {
        $this->receivers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getReceivers(): Collection
    {
        return $this->receivers;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }
}
