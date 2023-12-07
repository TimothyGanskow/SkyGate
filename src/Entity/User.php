<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $passwort = null;
    

    #[ORM\Column(length: 255)]
    private ?string $mailToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refreshToken = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];


    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZäöüÄÖÜ ._-]+(?:[-'\s][a-zA-ZäöüÄÖÜ ._-]+)*$/",
        match: true,
        message: "name cannot contain any special characters or numbers, except - and space",
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{2,6}$/',
        match: true,
        message: 'The phone number cannot contain any special characters, except -,+ or space',
    )]
    private ?string $telefon = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9]+(?:[-'\\s][a-zA-Z0-9]+)*$/",
        match: true,
        message: "postcode cannot contain any special characters, except -",
    )]
    private ?int $postcode = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZäöüÄÖÜ ._-]+(?:[-'\s][a-zA-ZäöüÄÖÜ ._-]+)*$/",
        match: true,
        message: "place cannot contain any special characters, except - and space",
    )]
    private ?string $place = null;

    #[ORM\Column]
    private ?bool $terms = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


    public function getPasswort(): ?string
    {
        return $this->passwort;
    }

    public function setPasswort(string $passwort): static
    {
        $this->passwort = $passwort;

        return $this;
    }

   
    public function getMailToken(): ?string
    {
        return $this->mailToken;
    }

    public function setMailToken(string $mailToken): static
    {
        $this->mailToken = $mailToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTelefon(): ?string
    {
        return $this->telefon;
    }

    public function setTelefon(string $telefon): static
    {
        $this->telefon = $telefon;

        return $this;
    }

    public function getPostcode(): ?int
    {
        return $this->postcode;
    }

    public function setPostcode(int $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function isTerms(): ?bool
    {
        return $this->terms;
    }

    public function setTerms(bool $terms): static
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->passwort;
    }

    public function setPassword(string $password): self
    {
        $this->passwort = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
