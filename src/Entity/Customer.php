<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^[\s\-\p{L}]*$/u",
     *     message="Invalid firstname"
     * )
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^[\s\-\p{L}]*$/u",
     *     message="Invalid lastname"
     * )
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *     pattern="/^[\,\\0-9\s\-\p{L}]*$/u",
     *     message="Invalid address"
     * )
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/^\+?[\d\s]{9,15}$/",
     *     message="Invalid telephone number"
     * )
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $telephone;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default"=false}, nullable=true)
     */
    private $duplicate = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Customer
     */
    public function setId(int $id): Customer
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return Customer
     */
    public function setFirstname(string $firstname): Customer
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return Customer
     */
    public function setLastname(string $lastname): Customer
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Customer
     */
    public function setAddress(string $address): Customer
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Customer
     */
    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     * @return Customer
     */
    public function setTelephone(string $telephone): Customer
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param \DateTime $registrationDate
     * @return Customer
     */
    public function setRegistrationDate(\DateTime $registrationDate): Customer
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDuplicate(): bool
    {
        return $this->duplicate;
    }

    /**
     * @param bool $duplicate
     * @return Customer
     */
    public function setDuplicate(bool $duplicate): Customer
    {
        $this->duplicate = $duplicate;
        return $this;
    }


}
