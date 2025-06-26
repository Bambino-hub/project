<?php

namespace App\Form\Fields\User;

use Symfony\Component\Validator\Constraints as Assert;


class EmailLoginField
{

    #[Assert\NotBlank()]
    private ?string $email = null;


    //geter
    /**
     * Get the value of email
     */
    public function getEmail(): string|null
    {
        return $this->email;
    }

    //seter

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email): static
    {
        $this->email = $email;

        return $this;
    }
}
