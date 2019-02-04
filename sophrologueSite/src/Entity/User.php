<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;// Permet de dire à Symfony que l'Entitée est un utilisateur
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // Permet d'utiliser l'annotation UniqueEntity() pour rendre une donnée unique
use Symfony\Component\Validator\Constraints as Assert; // Déclaration des annotations Constraints qui permettent mettre des conditions avant de valider les champs comme @Assert\length() pour la longeur min et max d'une chaine...


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields={"mail"},
 *  message="L'email que vous avez indiqué est déjà utilisé !" 
 * )
 */
class User implements UserInterface
//UserInterface = Représente l'interface que toutes les classes d'utilisateurs doivent implémenter pour créer de vrai utilisateurs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimun 8 caractères !")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Veuillez entrez le même mot de passe !")
     */
    public $confirm_password; // Permet de verifier si même mdp et le confirmer avant envoi 

    /**
     * @ORM\Column(type="array", length=80)
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Faq", mappedBy="user", orphanRemoval=true)
     */
    private $faqs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $token;  // 1 token pour vérifier l'accès du membre à l'espace de réinitialisation du mot de passe

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $passwordRequestedAt; // un champ datetime pour contrôler la validité du token
    



    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->faqs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {

        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /*
     * Get token
     */
    public function getToken()
    {
        return $this->token;
    }
    /*
     * Set token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /*
     * Get passwordRequestedAt
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }
    /*
     * Set passwordRequestedAt
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    // Ajout des 2 methods manquante de l'UserInterface = sinon fait tout planter !
    public function eraseCredentials()
    {

    }
    public function getSalt()
    {

    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Faq[]
     */
    public function getFaqs(): Collection
    {
        return $this->faqs;
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs[] = $faq;
            $faq->setUser($this);
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faqs->contains($faq)) {
            $this->faqs->removeElement($faq);
            // set the owning side to null (unless already changed)
            if ($faq->getUser() === $this) {
                $faq->setUser(null);
            }
        }

        return $this;
    }

}
