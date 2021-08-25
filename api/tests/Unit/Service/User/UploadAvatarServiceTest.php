<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Service\File\FileService;
use App\Service\User\UploadAvatarService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarServiceTest extends UserServiceTestBase
{

    /** @var FileService|MockObject */
    private $fileService;
    private UploadAvatarService $service;
    private string $mediaPath;

    public function setUp(): void
    {
        parent::setUp();

        $this->mediaPath   = 'http://storage.com/';
        $this->fileService = $this->getMockBuilder(FileService::class)->disableOriginalConstructor()->getMock();

        $this->service = new UploadAvatarService(
            $this->userRepository,
            $this->fileService,
            $this->mediaPath
        );
    }

    public function testUploadAvatar(): void
    {
        // Mock request object && file
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $file    = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        // Create user
        $user = new User('lereleTest', 'lerereTest@api.com');
        $user->setAvatar('testAvatar.png');


        // Validate in method $file = $this->fileService->validateFile($request, FileService::AVATAR_INPUT_NAME);
        $this->fileService
            ->expects($this->exactly(1))
            ->method('validateFile')
            ->with($request, FileService::AVATAR_INPUT_NAME)
            ->willReturn($file);

        // Validate in method $this->fileService->deleteFile($user->getAvatar());
        $this->fileService
            ->expects($this->exactly(1))
            ->method('deleteFile')
            ->with($user->getAvatar());

        // Validate in method  $this->fileService->deleteFile($user->getAvatar());
        $this->fileService
            ->expects($this->exactly(1))
            ->method('uploadFile')
            ->with($file, FileService::AVATAR_INPUT_NAME)
            ->willReturn('aaa.png');

        $response = $this->service->uploadAvatar($request, $user);

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals('aaa.png', $response->getAvatar());
    }

}