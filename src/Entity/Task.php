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
    private $is_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_file;


    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $contents;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date;

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
    private $start_date;

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
        return $this->end_date;
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
        return $this->is_date;
    }

    public function getIsFile()
    {
        return $this->is_file;
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
        return $this->start_date;
    }

    public function setContents($contents): void
    {
        $this->contents = $contents;
    }

    public function setEndDate($end_date): void
    {
        $this->end_date = $end_date;
    }

    public function setIsDate($is_date): void
    {
        $this->is_date = $is_date;
    }

    public function setIsFile($is_file): void
    {
        $this->is_file = $is_file;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setSection($section): void
    {
        $this->section = $section;
    }

    public function setStartDate($start_date): void
    {
        $this->start_date = $start_date;
    }
}
