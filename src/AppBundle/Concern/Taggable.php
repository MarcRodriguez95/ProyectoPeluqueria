<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 23/05/2017
 * Time: 20:29
 */

namespace AppBundle\Concern;


use AppBundle\Entity\Tag;

trait Taggable {

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Grafikart\TagBundle\Entity\Tag", cascade={"persist"})
     */
    private $tags;

    /**
     * Add tag
     *
     * @param \AppBundle\Entity\Tag $tag
     *
     * @return Tag
     */
    public function addTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Tag $tag
     */
    public function removeTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

}