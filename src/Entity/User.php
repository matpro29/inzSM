<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\OneToMany(targetEntity="UserCourse", mappedBy="user")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="owner")
     */
    private $courses_own;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=96)
     */
    private $password;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $username;

    public function __construct()
    {
        $this->courses_own = new ArrayCollection();
        $this->roles = array('ROLE_USER');
        $this->courses = new ArrayCollection();
    }

    public function eraseCredentials()
    {
    }

    public function getCourses()
    {
        return $this->courses;
    }

    public function getCoursesOwn()
    {
        return $this->courses_own;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUserFormLabel()
    {
        return $this->id . ' ' . $this->username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
        ));
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function setRoles($roles)
    {
        $this->roles = array($roles);
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
            ) = unserialize($serialized);
    }
}
