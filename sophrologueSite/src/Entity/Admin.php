<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; // Déclaration des annotations Constraints qui permettent mettre des conditions avant de valider les champs comme @Assert\length() pour la longeur min et max d'ue chaine...
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // Permet d'utiliser l'annotation UniqueEntity() pour rendre une donnée unique


/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminRepository")
 * @UniqueEntity(
 *  fields={"mail"},
 *  message="L'email que vous avez indiqué est déjà utilisé !" 
 * )
 */
class Admin implements UserInterface //UserInterface = Représente l'interface que toutes les classes d'utilisateurs doivent implémenter pour créer de vrai utilisateurs
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
     * @ORM\Column(type="array", length=80, nullable=true)
     */
    private $role;

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

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(?array $role): self
    {
        $this->role = $role;

        return $this;
    }

    // Ajout des methods manquante de l'UserInterface = sinon fait tout planter !
    public function eraseCredentials()
    {

    }
    public function getSalt()
    {

    }
    public function getRoles()
    {
        return $this->role;
    }
    public function getUsername()
    {
        return 'Admin';
    }
}
