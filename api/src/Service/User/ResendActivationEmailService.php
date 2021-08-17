<?php

namespace App\Service\User;

use App\Exception\User\UserIsActiveException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class ResendActivationEmailService
{
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function resendEmail(string $email)
    {
        // find email in DB
        $user = $this->userRepository->findOneByEmailOrFail($email);

        // Check if user 'isActive'
        if ($user->isActive()){
            throw UserIsActiveException::fromEmail($email);
        }
        // create new token
        $user->setToken(\sha1(\uniqid()));

        // save token in DB
        $this->userRepository->save($user);

        // Send message to rabbitmq
        $this->messageBus->dispatch(
            new UserRegisteredMessage(
                $user->getId(),
                $user->getName(),
                $user->getEmail(),
                $user->getToken()
            ),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );

    }

}