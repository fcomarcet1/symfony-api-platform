<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class GroupRequest
{
    public const ACCEPTED ='accepted';
    public const PENDING = 'pending';

    public string $id;
    public Group $group;
    public User $user;
    public string $status;
    public string $token;
    public ?\DateTime $acceptedAt;


    public function __construct(Group $group, User $user)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->group = $group;
        $this->user = $user;
        $this->status = self::PENDING;
        $this->token = \sha1(\uniqid());
        $this->acceptedAt = null;
    }


    public function getId(): string
    {
        return $this->id;
    }


    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }


    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }


    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }


    public function getAcceptedAt(): ?\DateTime
    {
        return $this->acceptedAt;
    }


    public function setAcceptedAt(?\DateTime $acceptedAt): void
    {
        $this->acceptedAt = $acceptedAt;
    }






}