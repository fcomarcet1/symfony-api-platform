<?php
declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Exception\Group\OwnerCannotBeDeletedException;
use App\Exception\Group\UserNotMemberOfGroupException;
use Symfony\Component\HttpFoundation\JsonResponse;

class RemoveUserTest extends GroupTestBase
{


    // Happy path case invite peter to grian
    public function testRemoveUserFromGroup(): void
    {
        // We need add user to group before test.
        // Add brian to peter group
        $this->addUserToGroup();

        $payload = ['userId' => $this->getBrianId()];

        self::$peter->request(
            'PUT',
            \sprintf('%s/%s/remove_user', $this->endpoint, $this->getBrianGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Check code 200, message from response
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('The user has been removed from group!', $responseData['message']);


    }

    public function testRemoveTheOwner(): void
    {
        // add brian to peter group
        $this->addUserToGroup();

        $payload = ['userId' => $this->getPeterId()];

        self::$peter->request(
            'PUT',
            \sprintf('%s/%s/remove_user', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Check code 200, message from response
        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(
            'The owner cannot be deleted from a group. Try deleting the group instead.',
            $responseData['message']
        );
        $this->assertEquals(OwnerCannotBeDeletedException::class, $responseData['class']);

    }

    // remove peter from brian group
    public function testRemoveNotAMember(): void
    {

        $payload = ['userId' => $this->getPeterId()];

        self::$brian->request(
            'PUT',
            \sprintf('%s/%s/remove_user', $this->endpoint, $this->getBrianGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response     = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        // Check code 200, message from response, exception
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals('This user is not member of this group.', $responseData['message']);
        $this->assertEquals(UserNotMemberOfGroupException::class, $responseData['class']);


    }

    // Add brian to peter group
    private function addUserToGroup(): void
    {
        $payload = [
            'userId' => $this->getBrianId(),
            'token'  => '234567',
        ];

        self::$peter->request(
            'PUT',
            \sprintf('%s/%s/accept_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );
    }
}