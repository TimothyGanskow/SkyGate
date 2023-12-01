<?php

namespace App\Entity;

use App\Repository\RegistryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistryRepository::class)]
class Registry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $telefon = null;

    #[ORM\Column]
    private ?int $postcode = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column]
    private ?bool $terms = null;

    #[ORM\Column(nullable: true)]
    private ?bool $mailConfirmed = null;

    #[ORM\OneToOne(mappedBy: 'registry', cascade: ['persist', 'remove'])]
    private ?Users $registry = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

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

    public function isMailConfirmed(): ?bool
    {
        return $this->mailConfirmed;
    }

    public function setMailConfirmed(?bool $mailConfirmed): static
    {
        $this->mailConfirmed = $mailConfirmed;

        return $this;
    }

    public function getRegistry(): ?Users
    {
        return $this->registry;
    }

    public function setRegistry(Users $registry): static
    {
        // set the owning side of the relation if necessary
        if ($registry->getRegistry() !== $this) {
            $registry->setRegistry($this);
        }

        $this->registry = $registry;

        return $this;
    }

}
