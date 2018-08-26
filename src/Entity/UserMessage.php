<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserMessageRepository")
 */
class UserMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="users")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message_receive")
     */
    private $user_receiver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="message_send")
     */
    private $user_sender;

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getUserReceiver()
    {
        return $this->user_receiver;
    }

    public function getUserSender()
    {
        return $this->user_sender;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function setUserReceiver($user_receiver): void
    {
        $this->user_receiver = $user_receiver;
    }

    public function setUserSender($user_sender): void
    {
        $this->user_sender = $user_sender;
    }
}
