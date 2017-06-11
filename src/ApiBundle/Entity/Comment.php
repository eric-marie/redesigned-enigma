<?php

namespace ApiBundle\Entity;

use ApiBundle\ParentClasses\ParentEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment
 * @package ApiBundle\Entity
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="id_article", columns={"id_article"})})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CommentRepository")
 */
class Comment extends ParentEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(name="id_article", referencedColumnName="id")
     */
    private $idArticle;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get idArticle
     *
     * @return Article
     */
    public function getIdArticle()
    {
        return $this->idArticle;
    }

    /**
     * Set idArticle
     *
     * @param Article $idArticle
     *
     * @return Comment
     */
    public function setIdArticle(Article $idArticle = null)
    {
        $this->idArticle = $idArticle;

        return $this;
    }

    /**
     * get idUser
     *
     * @return User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idUser
     *
     * @param User $idUser
     *
     * @return Comment
     */
    public function setIdUser(User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Converti l'entité en un tableau associatif pour la réponse en JSON de l'API
     *
     * @return array
     */
    public function getJsonArray()
    {
        return [
            'id' => $this->getId(),
            'auteur' => $this->getIdUser(),
            'content' => $this->getContent()
        ];
    }
}
