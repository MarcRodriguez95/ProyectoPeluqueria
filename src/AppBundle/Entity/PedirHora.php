<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedirHora
 *
 * @ORM\Table(name="pedir_hora")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PedirHoraRepository")
 */
class PedirHora
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="bgColor", type="string", length=255)
     */
    private $bgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="fgColor", type="string", length=255)
     */
    private $fgColor;

    /**
     * @var string
     *
     * @ORM\Column(name="cssClass", type="string", length=255)
     */
    private $cssClass;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDatetime", type="string", length=255)
     */
    private $startDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDatetime", type="string", length=255)
     */
    private $endDatetime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="allDay", type="string", length=255)
     */
    private $allDay = false;

    /**
     * @var array
     *
     * @ORM\Column(name="otherFields", type="string", length=255)
     */
    private $otherFields = array();

    public function __construct($title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false)
    {
        $this->title = $title;
        $this->startDatetime = $startDatetime;
        $this->setAllDay($allDay);

        if ($endDatetime === null && $this->allDay === false) {
            throw new \InvalidArgumentException("Must specify an event End DateTime if not an all day event.");
        }

        $this->endDatetime = $endDatetime;
    }
    /**
     * Convert calendar event details to an array
     *
     * @return array $event
     */
    public function toArray()
    {
        $event = array();

        if ($this->id !== null) {
            $event['id'] = $this->id;
        }

        $event['title'] = $this->title;
        $event['start'] = $this->startDatetime->format("Y-m-d\TH:i:sP");

        if ($this->url !== null) {
            $event['url'] = $this->url;
        }

        if ($this->bgColor !== null) {
            $event['backgroundColor'] = $this->bgColor;
            $event['borderColor'] = $this->bgColor;
        }

        if ($this->fgColor !== null) {
            $event['textColor'] = $this->fgColor;
        }

        if ($this->cssClass !== null) {
            $event['className'] = $this->cssClass;
        }
        if ($this->endDatetime !== null) {
            $event['end'] = $this->endDatetime->format("Y-m-d\TH:i:sP");
        }

        $event['allDay'] = $this->allDay;
        foreach ($this->otherFields as $field => $value) {
            $event[$field] = $value;
        }

        return $event;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setBgColor($color)
    {
        $this->bgColor = $color;
    }

    public function getBgColor()
    {
        return $this->bgColor;
    }

    public function setFgColor($color)
    {
        $this->fgColor = $color;
    }

    public function getFgColor()
    {
        return $this->fgColor;
    }

    public function setCssClass($class)
    {
        $this->cssClass = $class;
    }

    public function getCssClass()
    {
        return $this->cssClass;
    }

    public function setStartDatetime(\DateTime $start)
    {
        $this->startDatetime = $start;
    }

    public function getStartDatetime()
    {
        return $this->startDatetime;
    }

    public function setEndDatetime(\DateTime $end)
    {
        $this->endDatetime = $end;
    }

    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

    public function setAllDay($allDay = false)
    {
        $this->allDay = (boolean) $allDay;
    }

    public function getAllDay()
    {
        return $this->allDay;
    }
    /**
     * @param string $name
     * @param string $value
     */
    public function addField($name, $value)
    {
        $this->otherFields[$name] = $value;
    }
    /**
     * @param string $name
     */
    public function removeField($name)
    {
        if (!array_key_exists($name, $this->otherFields)) {
            return;
        }
        unset($this->otherFields[$name]);
    }
}

