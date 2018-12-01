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
     * @ORM\Column(type="string", length=256)
     */
    private $city;

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
     * @ORM\Column(type="string", length=256)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="user")
     */
    private $files;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $flat;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $house;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $indeks;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="owner")
     */
    private $messagesSend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $noticeDate;

    /**
     * @Assert\Length(max=4096)
     */
    private $oldPassword;

    /**
     * @ORM\Column(type="string", length=96)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $PESEL;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $secondName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSectionGrade", mappedBy="user")
     */
    private $sectionsGrades;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $street;

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

    public function getCity()
    {
        return $this->city;
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getFlat()
    {
        return $this->flat;
    }

    public function getHouse()
    {
        return $this->house;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIndeks()
    {
        return $this->indeks;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getMessagesSend(): Collection
    {
        return $this->messagesSend;
    }

    public function getNoticeDate()
    {
        return $this->noticeDate;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPESEL()
    {
        return $this->PESEL;
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

    public function getSecondName()
    {
        return $this->secondName;
    }

    public function getSectionsGrades(): Collection
    {
        return $this->sectionsGrades;
    }

    public function getStreet()
    {
        return $this->street;
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

    public function setCity($city): void
    {
        $this->city = $city;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setFlat($flat): void
    {
        $this->flat = $flat;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setHouse($house): void
    {
        $this->house = $house;
    }

    public function setIndeks($indeks): void
    {
        $this->indeks = $indeks;
    }

    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setNoticeDate($noticeDate): void
    {
        $this->noticeDate = $noticeDate;
    }

    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setPESEL($PESEL): void
    {
        $this->PESEL = $PESEL;
    }

    public function setPlainPassword($password): void
    {
        $this->plainPassword = $password;
    }

    public function setRoles($roles): void
    {
        $this->roles = array($roles);
    }

    public function setSecondName($secondName): void
    {
        $this->secondName = $secondName;
    }

    public function setStreet($street): void
    {
        $this->street = $street;
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
