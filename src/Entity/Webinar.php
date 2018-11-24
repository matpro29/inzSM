<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebinarRepository")
 */
class Webinar
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="webinars")
     */
    private $course;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $video;

    public function getCourse()
    {
        return $this->course;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function setCourse($course): void
    {
        $this->course = $course;
    }

    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setVideo($video): void
    {
        $this->video = $video;
    }
}
