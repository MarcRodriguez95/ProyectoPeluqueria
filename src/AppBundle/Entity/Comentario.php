<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comentario
 *
 * @ORM\Table(name="comentario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioRepository")
 */
class Comentario
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
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;



    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Post", inversedBy="comentario")
     */
    private $post;

    /**
     * @ORM\Column(name="usuario", type="string", length=255)
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="comment")
     */
    private $usuario;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }


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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comentario
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }



    /**
     * Set post
     * @return Comentario
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * Get post
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set usuario
     * @return Comentario
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * Get usuario
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }



    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comentario
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param string $updatedAt
     *
     * @return Comentario
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}

