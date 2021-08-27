<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Group
{
    private string $id;
    private string $name;
    private User $owner;
    private Collection $users;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;


    public function __construct(string $name, User $owner)
    {
        $this->id        = Uuid::v4()->toRfc4122();
        $this->name      = $name;
        $this->owner     = $owner;
        $this->users     = new ArrayCollection([$owner]);
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
        $owner->addGroup($this);

    }
    
    public function getId(): string
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getOwner(): User
    {
        return $this->owner;
    }


    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }


    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }


    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }


    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // Add user to group
    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }
        $this->users->add($user);
    }

    // remove user from group
    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
    }

    // Check if a group contains a user
    public function containsUser(User $user): bool
    {
        return $this->users->contains($user);
    }

    /**
     * Check if user is a group owner
     * A user will be the owner of the group if he created it himself.
     */
    public function isOwnerBy(User $user): bool
    {
        return $this->owner->getId() === $user->getId();
    }
}