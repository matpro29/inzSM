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
     * @ORM\OneToMany(targetEntity="App\Entity\UserConversation", mappedBy="user")
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCourse", mappedBy="user")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCourseGrade", mappedBy="user")
     */
    private $coursesGrades;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Course", mappedBy="owner")
     */
    private $coursesOwn;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="user")
     */
    private $files;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="owner")
     */
    private $messagesSend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $noticeDate;

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
     * @ORM\OneToMany(targetEntity="App\Entity\UserSectionGrade", mappedBy="user")
     */
    private $sectionsGrades;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $username;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->coursesGrades = new ArrayCollection();
        $this->coursesOwn = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->roles = array('ROLE_USER');
        $this->sectionsGrades = new ArrayCollection();
        $this->messagesSend = new ArrayCollection();
    }

    public function eraseCredentials()
    {
    }

    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function getCoursesGrades(): Collection
    {
        return $this->coursesGrades;
    }

    public function getCoursesOwn(): Collection
    {
        return $this->coursesOwn;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getMessagesSend(): Collection
    {
        return $this->messagesSend;
    }

    public function getNoticeDate()
    {
        return $this->noticeDate;
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

    public function getSectionsGrades(): Collection
    {
        return $this->sectionsGrades;
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

    public function setNoticeDate($noticeDate): void
    {
        $this->noticeDate = $noticeDate;
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
