<?php

declare(strict_types=1);

namespace App\Service\User;


use App\Messenger\Message\RequestResetPasswordMessage;
use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class RequestResetPasswordService
{
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        UserRepository $userRepository,
        MessageBusInterface $messageBus
    ) {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function sendResetPassword(Request $request)
    {

        // Check email from request ang get user
        $user = $this->userRepository->findOneByEmailOrFail(
            RequestService::getField($request, 'email')
        );

        //set resetTokenPassword and save
        $user->setResetPasswordToken(\sha1(\uniqid()));
        $this->userRepository->save($user);

        // Send message to rabbitmq
        $this->messageBus->dispatch(
            new RequestResetPasswordMessage(
                $user->getId(),
                $user->getEmail(),
                $user->getResetPasswordToken()
            ),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
    }
}