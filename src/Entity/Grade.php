<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GradeRepository")
 */
class Grade
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCourseGrade", mappedBy="grade")
     */
    private $coursesGrades;

    /**
     * @ORM\Column(type="float")
     */
    private $grade;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSectionGrade", mappedBy="grade")
     */
    private $sectionsGrades;

    public function __construct()
    {
        $this->coursesGrades = new ArrayCollection();
        $this->sectionsGrades = new ArrayCollection();
    }

    public function getCoursesGrades(): Collection
    {
        return $this->coursesGrades;
    }

    public function getGrade()
    {
        return $this->grade;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSectionsGrades(): Collection
    {
        return $this->sectionsGrades;
    }

    public function setGrade($grade): void
    {
        $this->grade = $grade;
    }
}
