<?php

namespace App\Entity;

use App\Repository\UserspermissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserspermissionRepository::class)]
class Userspermission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $permission = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'permission', cascade: ['persist', 'remove'])]
    private ?Users $permissionid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPermission(): ?int
    {
        return $this->permission;
    }

    public function setPermission(int $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPermissionid(): ?Users
    {
        return $this->permissionid;
    }

    public function setPermissionid(Users $permissionid): static
    {
        // set the owning side of the relation if necessary
        if ($permissionid->getPermission() !== $this) {
            $permissionid->setPermission($this);
        }

        $this->permissionid = $permissionid;

        return $this;
    }

}
