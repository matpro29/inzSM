<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Notice
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="notices")
     */
    private $course;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    public $notice;

    public function getCourse()
    {
        return $this->course;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNotice()
    {
        return $this->notice;
    }

    public function setCourse($course): void
    {
        $this->course = $course;
    }

    public function setNotice($notice): void
    {
        $this->notice = $notice;
    }
}
