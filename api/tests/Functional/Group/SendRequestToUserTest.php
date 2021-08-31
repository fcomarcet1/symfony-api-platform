<?php
declare(strict_types=1);

namespace App\Tests\Functional\Group;

use Symfony\Component\HttpFoundation\JsonResponse;

class SendRequestToUserTest extends GroupTestBase
{
    // Message expected:
    public const MESSAGE_EXPECTED_SEND_REQUEST_USER = 'The (email) request has been sent to invite to group!';
    public const MESSAGE_EXCEPTION_NOT_OWNER_OF_GROUP = 'You cannot invite others members if are not owner of this group';
    public const MESSAGE_EXCEPTION_USER_ALREADY_MEMBER = 'This user is already member of the group';


    // Happy path case
    public function testSendRequestToUser(): void
    {
        $payload = ['email' => 'roger@api.com'];

        self::$peter->request(
            'PUT',
            \sprintf('%s/%s/send_request',$this->endpoint,$this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Check response code 200 OK, check message
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::MESSAGE_EXPECTED_SEND_REQUEST_USER, $responseData['message']);
    }

    // try to invite another group when user in not owner of this group.
    public function testSendAnotherGroupRequestToUser(): void
    {
        $payload = ['email' => 'roger@api.com'];

        self::$brian->request(
            'PUT',
            \sprintf('%s/%s/send_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(self::MESSAGE_EXCEPTION_NOT_OWNER_OF_GROUP, $responseData['message']);
    }

    // Try to invite a user what already exists in this group
    public function testSendRequestToAnAlreadyMember(): void
    {
        $payload = ['email' => 'peter@api.com'];

        self::$peter->request(
            'PUT',
            \sprintf('%s/%s/send_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(self::MESSAGE_EXCEPTION_USER_ALREADY_MEMBER, $responseData['message']);
    }
}