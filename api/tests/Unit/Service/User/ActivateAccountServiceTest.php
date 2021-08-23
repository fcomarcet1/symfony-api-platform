<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ActivateAccountService;
use Symfony\Component\Uid\Uuid;

class ActivateAccountServiceTest extends UserServiceTestBase
{

    /** @var ActivateAccountService */
    private ActivateAccountService $service;

    public function setUp(): void
    {
        parent::setUp();
        // Instanciamos servicio
        $this->service = new ActivateAccountService($this->userRepository);
    }

    // Test happy path all should be OK
    public function testActivateAccount(): void
    {
        // El metodo recibe id, token:
        $id = Uuid::v4()->toRfc4122();
        $token = \sha1(\uniqid());

        // create user
        $user = new User('userTest', 'userTest@test.com');

        //test query
        // $user = $this->userRepository->findOneInactiveByIdAndTokenOrFail($id, $token);
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willReturn($user)
        ;

       $user = $this->service->activate($id, $token);

       $this->assertInstanceOf(User::class, $user);
       $this->assertNull($user->getToken());
       $this->assertTrue($user->isActive());

    }


    public function testForNonExistingUser(): void
    {
        $id = Uuid::v4()->toRfc4122();
        $token = \sha1(\uniqid());

        // Check query when user not exists
        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willThrowException(
                new UserNotFoundException(\sprintf('User with id %s and token %s not found', $id, $token))
            );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(\sprintf('User with id %s and token %s not found', $id, $token));

        $this->service->activate($id, $token);
    }
}