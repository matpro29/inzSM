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
     * @ORM\Column(type="integer")
     */
    private $id_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="types")
     * @ORM\JoinColumn(name="id_type", referencedColumnName="id")
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

    public function getIdType()
    {
        return $this->id_type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubjectName()
    {
        return $this->id . ' ' . $this->name . ' ' . $this->type->getName();
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

    public function setIdType($id_type)
    {
        $this->id_type = $id_type;
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
