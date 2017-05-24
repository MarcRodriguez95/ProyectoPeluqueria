<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * starratingsystem
 *
 * @ORM\Table(name="starratingsystem")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\starratingsystemRepository")
 */
class starratingsystem
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
     * @var int
     *
     * @ORM\Column(name="media", type="integer")
     */
    private $media;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrrate", type="integer")
     */
    private $nbrrate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set media
     *
     * @param integer $media
     *
     * @return starratingsystem
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return int
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     *
     * @return starratingsystem
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set nbrrate
     *
     * @param integer $nbrrate
     *
     * @return starratingsystem
     */
    public function setNbrrate($nbrrate)
    {
        $this->nbrrate = $nbrrate;

        return $this;
    }

    /**
     * Get nbrrate
     *
     * @return int
     */
    public function getNbrrate()
    {
        return $this->nbrrate;
    }
}

