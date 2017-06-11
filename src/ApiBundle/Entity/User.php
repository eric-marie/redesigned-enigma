<?php

namespace ApiBundle\Entity;

use ApiBundle\ParentClasses\ParentEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package ApiBundle\Entity
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Le nom d'utilisateur est déjà utilisé")
 * @UniqueEntity(fields={"email"}, message="L'adresse email est déjà utilisée")
 */
class User extends ParentEntity implements AdvancedUserInterface
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=180, nullable=false)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Length(
     *      min = 1,
     *      max = 180,
     *      minMessage = "L'email doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "L'email est limité à {{ limit }} caractères"
     * )
     * @Assert\Email(message="L'email n'est pas valide")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Le nom d'utilisateur est obligatoire")
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "Le nom d'utilisateur doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le nom d'utilisateur est limité à {{ limit }} caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+$/",
     *     message="Le nom d'utilisateur ne peut contenir que des lettres"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     * @Assert\Length(
     *      min = 8,
     *      max = 4096,
     *      minMessage = "Le mot de passe doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le mot de passe est limité à {{ limit }} caractères"
     * )
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="account_locked", type="boolean", nullable=false)
     */
    private $accountLocked = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable=false)
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Comment", mappedBy="idUser")
     */
    private $comments;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Article", mappedBy="idUser")
     */
    private $articles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = ['ROLE_USER'];

        $this->comments = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    /**
     * N'est pas utile avec un cryptage en bcrypt
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Supprime les information sensibles à ne pas faire persister
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        if(null == $this->lastLogin)
            return true;

        $today = new \DateTime();
        return 365 > $this->lastLogin->diff($today)->days;
    }

    /**
     * @return bool
     */
    public function isAccountLocked()
    {
        return $this->accountLocked;
    }

    /**
     * @param bool $accountLocked
     */
    public function setAccountLocked($accountLocked)
    {
        $this->accountLocked = $accountLocked;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return !$this->isAccountLocked();
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
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
     * @return User
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param ArrayCollection $articles
     *
     * @return User
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

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
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'roles' => $this->getRoles(),
            'articles' => $this->getArticles()->toArray(),
            'comments' => $this->getComments()->toArray()
        ];

    }
}