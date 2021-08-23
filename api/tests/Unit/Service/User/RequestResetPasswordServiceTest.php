<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Service\User\RequestResetPasswordService;
use Symfony\Component\Messenger\Envelope;

class RequestResetPasswordServiceTest extends UserServiceTestBase
{
    private RequestResetPasswordService $service;

    public function setUp(): void
    {
     parent::setUp();

     $this->service = new RequestResetPasswordService(
         $this->userRepository,
         $this->messageBus
     );
    }

    /**
     * Happy path case. This method receive email(string)
     */
    public function testRequestResetPassword(): void
    {

        $email = 'user@api.com';
        $user = new User('name', $email);

        // if all is ok return User
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn($user)
        ;

        // Create Mock message
        $message = $this
            ->getMockBuilder(UserRegisteredMessage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        // test send message to rabbit
        $this->messageBus
            ->expects($this->exactly(1))
            ->method('dispatch')
            ->with($this->isType('object'), $this->isType('array'))
            ->willReturn(new Envelope($message));

        $this->service->sendResetPassword($email);
    }

    /**
     * In this case user not exists in DB, expected UserNotFoundException
     */
    public function testRequestResetPasswordForNonExistingUser(): void
    {
        $email = 'user23658@api.com';

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willThrowException(new UserNotFoundException())
        ;

        $this->expectException(UserNotFoundException::class);

        $this->service->sendResetPassword($email);
    }

}