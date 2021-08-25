<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadAvatarActionTest extends UserTestBase
{
    public function testUploadAvatar(): void
    {
        // Create file for send to endpoint
        $avatar = new UploadedFile(
            __DIR__ . '/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$peter->request(
            'POST',
            \sprintf('%s/%s/avatar', $this->endpoint, $this->getPeterId()),
            [],
            ['avatar' => $avatar]
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

}