<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteUserTest extends UserTestBase
{
    
    public function testDeleteUser(): void
    {
        // Request with peter for delete peter account
        self::$peter->request(
            'DELETE',
            \sprintf('%s/%s', $this->endpoint, $this->getPeterId())
        );
        // get response
        $response = self::$peter->getResponse();

        // Check response code 204 NO CONTENT
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAnotherUser(): void
    {
        // Request with brian for delete peter account
        self::$brian->request(
            'DELETE',
            \sprintf('%s/%s', $this->endpoint, $this->getPeterId())
        );

        $response = self::$brian->getResponse();

        // Check Forbidden access 403
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
