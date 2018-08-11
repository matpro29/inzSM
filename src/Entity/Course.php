<?php

namespace App\Entity;

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

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setIdOwner($id_owner)
    {
        $this->idOwner = $id_owner;
    }

    public function setIdSubject($id_subject)
    {
        $this->idSubject = $id_subject;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
}
