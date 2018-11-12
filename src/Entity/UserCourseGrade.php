<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCourseGradeRepository")
 */
class UserCourseGrade
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="grades")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Grade", inversedBy="coursesGrades")
     */
    private $grade;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="coursesGrades")
     */
    private $user;

    public function getCourse()
    {
        return $this->course;
    }

    public function getGrade()
    {
        return $this->grade;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCourse($course): void
    {
        $this->course = $course;
    }

    public function setGrade($grade): void
    {
        $this->grade = $grade;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
