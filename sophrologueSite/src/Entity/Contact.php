<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert; // Déclaration des annotations Constraints qui permettent mettre des conditions avant de valider les champs comme @Assert\length() pour la longeur min et max d'ue chaine...


class Contact
{
    /**
     * @var string|null
     * @Assert\NotBlank(message=" Veuillez mettre un nom !")
     * @Assert\Length(min=2, max=100)
     */
    private $nom;

    /**
     * @var string|null
     * @Assert\NotBlank(message=" Veuillez mettre un email !")
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string|null
     * @Assert\Regex(pattern="/[0-9]{10}/", message=" Veuillez entrez un numéro valide !")
     */
    private $telephone;

    
    private $rdv;


    private $lieu;

    /**
     * @var string|null
     * @Assert\NotBlank(message=" Veuillez écrire un message !")
     * @Assert\Length(min=10)
     */
    private $message;


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getRdv(): ?string
    {
        return $this->rdv;
    }

    public function setRdv(string $rdv): self
    {
        $this->rdv = $rdv;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

}
