<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCourseRepository")
 */
class UserCourse
{
    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="users")
     * @ORM\JoinColumn(name="id_course", referencedColumnName="id")
     */
    private $course;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_course;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courses")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
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

    public function getIdCourse()
    {
        return $this->id_course;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCourse($course)
    {
        $this->course = $course;
    }

    public function setIdCourse($id_course)
    {
        $this->id_course = $id_course;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
