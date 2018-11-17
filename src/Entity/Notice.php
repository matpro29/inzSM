<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoticeRepository")
 */
class Notice
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="notices")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", nullable=true)
     */
    private $course;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    private $endDateString;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    public $notice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    public function getCourse()
    {
        return $this->course;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getEndDateString()
    {
        return $this->endDateString;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNotice()
    {
        return $this->notice;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setCourse($course): void
    {
        $this->course = $course;
    }

    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setEndDateString($endDateString): void
    {
        $this->endDateString = $endDateString;
    }

    public function setNotice($notice): void
    {
        $this->notice = $notice;
    }

    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }
}
