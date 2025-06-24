<?php

namespace App\Form\Fields\Public;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('email', message: 'Cet email est déjà utilisé.', entityClass: User::class)]
class UserRegistrationField
{

    #[Assert\NotBlank()]
    private ?string $email = null;

    #[Assert\NotBlank()]
    private ?string $password = null;

    #[Assert\NotBlank()]
    private ?string $pseudonyme = null;


    // setters

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of pseudonyme
     *
     * @return  self
     */
    public function setPseudonyme(?string $pseudonyme): static
    {
        $this->pseudonyme = $pseudonyme;

        return $this;
    }


    // getters

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Get the value of password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }



    /**
     * Get the value of pseudonyme
     */
    public function getPseudonyme(): ?string
    {
        return $this->pseudonyme;
    }
}
