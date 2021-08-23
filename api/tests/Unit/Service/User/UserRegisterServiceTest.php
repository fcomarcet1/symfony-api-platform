<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service\User;


use App\Entity\User;
use App\Exception\Password\PasswordException;
use App\Exception\User\UserAlreadyExistException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Service\User\UserRegisterService;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Envelope;

class UserRegisterServiceTest extends UserServiceTestBase
{
    private UserRegisterService $service;

    // Initialize dependencies.
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UserRegisterService(
            $this->userRepository,
            $this->encoderService,
            $this->messageBus
        );
    }

    // Happy path
    public function testUserRegister(): void
    {
        // Create payload
        $name = 'username';
        $email = 'username@api.com';
        $password = '123456';

        /**
         * In service, we send this message to rabbit
         *  $this->messageBus->dispatch(
         *       new UserRegisteredMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
         *       [new AmqpStamp(RoutingKey::USER_QUEUE)]
         *   );
         */
        // Create mock message
        $message = $this->getMockBuilder(UserRegisteredMessage::class)
                        ->disableOriginalConstructor()
                        ->getMock()
        ;

        $this->messageBus
            ->expects($this->exactly(1))
            ->method('dispatch')
            ->with($this->isType('object'), $this->isType('array'))
            ->willReturn(new Envelope($message))
        ;

        // Create user
        $user = $this->service->create($name, $email, $password);

        // Check instance of user, name, email
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
    }

    // Test for new user register with invalid password
    public function testUserRegisterForInvalidPassword(): void
    {
        $name = 'username';
        $email = 'username@api.com';
        $password = '123';

        /**
         * Case invalid password in
         *  -> public function generateEncodedPassword(UserInterface $user, string $password)
         * we can check params received is User(object), password(string)
         * ->with($this->isType('object'), $this->isType('string'))
         * and expected an exception PasswordException
         */
        $this->encoderService
            ->expects($this->exactly(1))
            ->method('generateEncodedPassword')
            ->with($this->isType('object'), $this->isType('string'))
            ->willThrowException(new PasswordException());

        // expected Exception
        $this->expectException(PasswordException::class);

        // call method service
        $this->service->create($name, $email, $password);
    }

    /**
     * Case user already exists, expected UserAlreadyExistException
     * When try save user in db, we need to check if user exists, this user
     * already exists throws exception, and for this reason whe use method save
     */
    public function testUserRegisterForAlreadyExistingUser(): void
    {
        // Create payload
        $name = 'username';
        $email = 'username@api.com';
        $password = '123456';

        // Check save method, expected ORMException
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($this->isType('object'))
            ->willThrowException(new ORMException()) //see phpDoc throws
            //->willThrowException(new UserAlreadyExistException())
        ;

        $this->expectException(UserAlreadyExistException::class);

        $this->service->create($name, $email, $password);
    }
}