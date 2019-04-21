<?php

namespace Auth\Entity;

use Auth\Contract\JwtSubjectInterface;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="\Auth\Repository\UserRepository")
 */
class User implements JwtSubjectInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * {@inheritDoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return User
     */
    public function setName($name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return User
     */
    public function setEmail($email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return User
     */
    public function setPassword($password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at instanceof \DateTime ? $this->created_at->format(DATE_ATOM) : null;
    }

    /**
     * @param mixed $created_at
     *
     * @return User
     */
    public function setCreatedAt($created_at): User
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at instanceof \DateTime ? $this->updated_at->format(DATE_ATOM) : null;
    }

    /**
     * @param mixed $updated_at
     *
     * @return User
     */
    public function setUpdatedAt($updated_at): User
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
