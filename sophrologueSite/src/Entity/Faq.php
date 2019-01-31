<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Déclaration des annotations Constraints qui permettent mettre des conditions avant de valider les champs comme @Assert\length() pour la longeur min et max d'ue chaine...


/**
 * @ORM\Entity(repositoryClass="App\Repository\FaqRepository")
 */
class Faq
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message=" Veuillez mettre une question !")
     */
    private $question;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message=" Veuillez mettre une réponse !")
     */
    private $reponse;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="faqs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
