<?php

namespace App\Form\Fields\Administration;

use Symfony\Component\Validator\Constraints as Assert;

class AdminRegistrationFields
{

    #[Assert\NotBlank()]

    private ?string $email = null;
    #[Assert\NotBlank()]

    private ?string $firstName = null;
    #[Assert\NotBlank()]

    private ?string $lastName = null;
    #[Assert\NotBlank()]

    private ?string $password = null;


    // setters
    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    // getters
    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }


    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }
}
