<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterActionTest extends UserTestBase
{
    public function testRegister(): void
    {
        $payload = [
            'name' => 'Stewie',
            'email' => 'stewie@api.com',
            'password' => '123456',
        ];

        // anonymous client(not authenticated)
        self::$client->request(
            'POST',
            \sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        // transform to array response (function TestBaseCase)
        $responseData = $this->getResponseData($response);
        $this->assertEquals($payload['email'], $responseData['email']);
    }

    public function testRegisterWithMissingParameters(): void
    {
        $payload = [
            'name' => 'Stewie',
            'password' => '123456',
        ];

        self::$client->request('POST', \sprintf('%s/register', $this->endpoint), [], [], [], \json_encode($payload));

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRegisterWithInvalidPassword(): void
    {
        $payload = [
            'name' => 'Stewie',
            'email' => 'stewie@api.com',
            'password' => '1',
        ];

        // anonymous client(not authenticated)
        self::$client->request(
            'POST',
            \sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRegisterWithNullParameters(): void
    {
        $payload = [
            'name' => null,
            'email' => null,
            'password' => null,
        ];

        // anonymous client(not authenticated)
        self::$client->request(
            'POST',
            \sprintf('%s/register', $this->endpoint),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
