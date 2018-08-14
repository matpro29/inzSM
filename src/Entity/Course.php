<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
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
    private $id_owner;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_subject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courses_own")
     * @ORM\JoinColumn(name="id_owner", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=96)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="courses")
     * @ORM\JoinColumn(name="id_subject", referencedColumnName="id")
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="UserCourse", mappedBy="course")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getCourseFormLabel()
    {
        return $this->id . ' ' . $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdOwner()
    {
        return $this->id_owner;
    }

    public function getIdSubject()
    {
        return $this->id_subject;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setIdOwner($id_owner)
    {
        $this->id_owner = $id_owner;
    }

    public function setIdSubject($id_subject)
    {
        $this->id_subject = $id_subject;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
}
