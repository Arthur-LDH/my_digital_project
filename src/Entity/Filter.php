<?php

namespace App\Entity;

class Filter{

    /**
    * @var string|null
    */
    private $name;


    /**
    * @var string|null
    */
    private $roles;

    /**
    * @var boolean|null
    */
    private $isVerified;

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of roles
     */
    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of roles
     */
    public function getRoles(): ?string
    {
        return $this->roles;
    }


    /**
     * Get the value of isVerified
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    /**
     * Set the value of isVerified
     */
    public function setIsVerified($isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}