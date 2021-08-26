<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateUserTest extends UserTestBase
{
    // For update only can be change a name

    // Happy path case
    public function testUpdateUser(): void
    {
        $payload = ['name' => 'Peter New'];

        self::$peter->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getPeterId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Check response 200 OK
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        // Check name -> name = new name
        $this->assertEquals($payload['name'], $responseData['name']);
    }


    // Try to modify name form another user
    public function testUpdateAnotherUser(): void
    {
        $payload = ['name' => 'Peter New'];

        self::$brian->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getPeterId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$brian->getResponse();
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
