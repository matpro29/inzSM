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
     * @ORM\Column(type="string", length=4096)
     */
    private $contents;

    /**
     * @ORM\Column(type="boolean")
     */
    private $file;

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

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSection()
    {
        return $this->section;
    }

    public function setContents($contents): void
    {
        $this->contents = $contents;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setSection($section): void
    {
        $this->section = $section;
    }
}
