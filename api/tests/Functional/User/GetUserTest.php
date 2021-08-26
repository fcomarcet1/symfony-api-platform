<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    // Happy path case
    public function testGetUser(): void
    {
        // Get id from peter
        $peterId = $this->getPeterId();

        // Request with peter
        self::$peter->request('GET', \sprintf('%s/%s', $this->endpoint, $peterId));

        // Get response and data(to array) from request
        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Check code 200, id, email
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($peterId, $responseData['id']);
        $this->assertEquals('peter@api.com', $responseData['email']);
    }

    // In this case try to get data from another user
    public function testGetAnotherUserData(): void
    {
        // Get id from peter
        $peterId = $this->getPeterId();

        // Request with brian and id from peter
        self::$brian->request('GET', \sprintf('%s/%s', $this->endpoint, $peterId));

        // Get response and data(to array) from request
        $response = self::$brian->getResponse();

        // Check Forbidden access 403
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());

    }
}
