<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFile;


    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $contents;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="task")
     */
    private $files;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="tasks")
     */
    private $section;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsDate()
    {
        return $this->isDate;
    }

    public function getIsFile()
    {
        return $this->isFile;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setContents($contents): void
    {
        $this->contents = $contents;
    }

    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setIsDate($isDate): void
    {
        $this->isDate = $isDate;
    }

    public function setIsFile($isFile): void
    {
        $this->isFile = $isFile;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setSection($section): void
    {
        $this->section = $section;
    }

    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }
}
