<?php

declare(strict_types=1);

namespace Mailer\Messenger\Message;

class RequestResetPasswordMessage
{
    private string $id;
    private string $email;
    private string $resetTokenPassword;


    public function __construct(string $id, string $email, string $resetTokenPassword)
    {
        $this->id                 = $id;
        $this->email              = $email;
        $this->resetTokenPassword = $resetTokenPassword;
    }


    public function getId(): string
    {
        return $this->id;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function getResetTokenPassword(): string
    {
        return $this->resetTokenPassword;
    }




}