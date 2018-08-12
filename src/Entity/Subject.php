<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 */
class Subject
{
    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="subject")
     */
    private $courses;

    /**
     * @ORM\Column(type="integer")
     */
    private $ects;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $description;

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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEcts()
    {
        return $this->ects;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubjectName()
    {
        return $this->id . ' ' . $this->name . ' ' . $this->type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setEcts($ects)
    {
        $this->ects = $ects;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}
