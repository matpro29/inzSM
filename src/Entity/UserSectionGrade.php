<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserSectionGradeRepository")
 */
class UserSectionGrade
{
    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Grade", inversedBy="sectionsGrades")
     */
    private $grade;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="grades")
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sectionsGrades")
     */
    private $user;

    public function getComment()
    {
        return $this->comment;
    }

    public function getGrade()
    {
        return $this->grade;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    public function setGrade($grade): void
    {
        $this->grade = $grade;
    }

    public function setSection($section): void
    {
        $this->section = $section;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
