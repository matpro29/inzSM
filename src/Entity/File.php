<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Column(type="string", length=255))
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
     */
    private $file;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255))
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="files")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="files")
     */
    private $user;

    public function getFile()
    {
        return $this->file;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setTask($task): void
    {
        $this->task = $task;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
