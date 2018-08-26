<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCourse", mappedBy="user")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Course", mappedBy="owner")
     */
    private $courses_own;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMessage", mappedBy="user_receiver")
     */
    private $message_receive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserMessage", mappedBy="user_sender")
     */
    private $message_send;

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
        $this->courses = new ArrayCollection();
        $this->courses_own = new ArrayCollection();
        $this->roles = array('ROLE_USER');
        $this->message_receive = new ArrayCollection();
        $this->message_send = new ArrayCollection();
    }

    public function eraseCredentials()
    {
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function getCoursesOwn(): Collection
    {
        return $this->courses_own;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessageReceive(): Collection
    {
        return $this->message_receive;
    }

    public function getMessageSend(): Collection
    {
        return $this->message_send;
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

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setPlainPassword($password): void
    {
        $this->plainPassword = $password;
    }

    public function setRoles($roles): void
    {
        $this->roles = array($roles);
    }

    public function setUsername($username): void
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
