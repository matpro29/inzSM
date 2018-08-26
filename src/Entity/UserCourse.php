<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCourseRepository")
 */
class UserCourse
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="users")
     */
    private $course;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="courses")
     */
    private $user;

    public function getCourse()
    {
        return $this->course;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCourse($course): void
    {
        $this->course = $course;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
