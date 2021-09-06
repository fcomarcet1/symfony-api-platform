<?php
declare(strict_types=1);

namespace App\Tests\Functional\Movement;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadFileTest extends MovementTestBase
{

    public function testUploadFile(): void
    {
        // Create file for send to endpoint
        $file = new UploadedFile(
            __DIR__ . '/../../../fixtures/ticket.jpg',
            'ticket.jpg'
        );

        // ^/api/v1/movements/{id}/upload_file
        self::$peter->request(
            'POST',
            \sprintf('%s/%s/upload_file', $this->endpoint, $this->getPeterMovementId()),
            [],
            ['file' => $file]
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

    }

    public function testUploadFileWithWrongInputName(): void
    {
        // Create file for send to endpoint
        $file = new UploadedFile(
            __DIR__ . '/../../../fixtures/ticket.jpg',
            'ticket.jpg'
        );

        // ^/api/v1/movements/{id}/upload_file
        self::$peter->request(
            'POST',
            \sprintf('%s/%s/upload_file', $this->endpoint, $this->getPeterMovementId()),
            [],
            ['invalid-input' => $file]
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    

}