<?php
declare(strict_types=1);

namespace App\Tests\Functional\User;


use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserGroupsTest extends UserTestBase
{
    // Happy path case
    public function testGetUserGroups(): void
    {
        // Get peter id
        $peterId = $this->getPeterId();

        // Request to get peter groups /api/v1/users/{id}/groups
        self::$peter->request(
            'GET',
            \sprintf('%s/%s/groups', $this->endpoint, $peterId)
        );

        // Get response from request && get data(to array) from response
        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Test Code 200 OK
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        // Test number of data collection = 1, because Peter only has one group
        $this->assertCount(1, $responseData['hydra:member']);

    }

    /**
     * Case for check Doctrine extension (sub resources security),
     * try to get information from other groups that brian do not belong to
     */
    public function testGetAnotherUserGroups(): void
    {
        // request from brian with peter id
        self::$brian->request(
            'GET',
            \sprintf('%s/%s/groups', $this->endpoint, $this->getPeterId())
        );

        // get response && responseData
        $response     = self::$brian->getResponse();
        $responseData = $this->getResponseData($response);

        // Test Forbidden access 403
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        // Test message error
        $this->assertEquals('You can\'t retrieve another user groups', $responseData['message']);


    }


}