<?php
declare(strict_types=1);

namespace App\Tests\Functional\Group;

use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateGroupTest extends GroupTestBase
{
    // Happy path case
    public function testUpdateGroup(): void
    {
        // Create payload, only can update field name y groups
        $payload = ['name' => 'New group name'];

        // Send request with peter client and peter id
        self::$peter->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Check Code 200 OK
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        // Check  group name field is updated with new name
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    // Test case to try update Group when user(brian),he is not the owner
    public function testUpdateAnotherGroup(): void
    {
        $payload = ['name' => 'New group name'];

        self::$brian->request(
            'PUT',
            \sprintf('%s/%s', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$brian->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}

