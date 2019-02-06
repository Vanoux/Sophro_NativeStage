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
    private $name;

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
    private $phone;

    /**
     * @var string|null
     * @Assert\NotBlank(message=" Veuillez écrire un message !")
     * @Assert\Length(min=10)
     */
    private $message;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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
