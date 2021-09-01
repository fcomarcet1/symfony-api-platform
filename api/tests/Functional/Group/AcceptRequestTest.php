<?php
declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Exception\GroupRequest\GroupRequestNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AcceptRequestTest extends GroupTestBase
{
    public const EXPECTED_MESSAGE_ACCEPT_REQUEST = 'The user has been added to the group';

    // Happy path case
    public function testAcceptRequest(): void
    {
        $payload = [
            'userId' => $this->getBrianId(),
            'token'  => '234567', // same as fixture
        ];

        // Send request for brian to invite peter group
        self::$brian->request(
            'PUT',
            \sprintf('%s/%s/accept_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        //get response
        $response     = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        // Check code 200 && message
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::EXPECTED_MESSAGE_ACCEPT_REQUEST, $responseData['message']);
    }

    /**
     *Case accepts a request that is already marked as accepted
     * (the user has already accepted the invitation).
     */
    public function testAcceptAnAlreadyAcceptedRequest(): void
    {
        // execute test happy path for change status to accepted
        $this->testAcceptRequest();

        $payload = [
            'userId' => $this->getBrianId(),
            'token'  => '234567', // same as fixture
        ];

        // Send request for brian to invite peter group
        self::$brian->request(
            'PUT',
            \sprintf('%s/%s/accept_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            \json_encode($payload)
        );

        //get response
        $response     = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        // Check code 200 && exception
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(GroupRequestNotFoundException::class, $responseData['class']);
    }
}