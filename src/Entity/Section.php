<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 */
class Section
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="sections")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSectionGrade", mappedBy="section")
     */
    private $grades;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="section")
     */
    private $tasks;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setCourse($course): void
    {
        $this->course = $course;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }
}
