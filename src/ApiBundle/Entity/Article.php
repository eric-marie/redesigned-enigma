<?php

namespace ApiBundle\Entity;

use ApiBundle\ParentClasses\ParentEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Article
 * @package ApiBundle\Entity
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ArticleRepository")
 */
class Article extends ParentEntity
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank(message="Le titre ne peut être vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Le titre doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le titre est limité à {{ limit }} caractères"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     *
     * @Assert\NotBlank(message="Le contenu ne peut être vide")
     * @Assert\Length(
     *      min = 30,
     *      max = 65535,
     *      minMessage = "Le contenu doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le contenu est limité à {{ limit }} caractères"
     * )
     */
    private $content;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Comment", mappedBy="idArticle")
     */
    private $comments;

    /**
     * Article constructor
     */
    public function __construct() {
        $this->comments = new ArrayCollection();
    }

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
     * @return Article
     */
    public function setIdUser(User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

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
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     *
     * @return Article
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

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
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'comments' => $this->getComments()->toArray()
        ];
    }
}
