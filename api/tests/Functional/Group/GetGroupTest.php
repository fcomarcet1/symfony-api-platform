<?php
declare(strict_types=1);

namespace App\Tests\Functional\Group;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupTest extends GroupTestBase
{

    // Happy path case -->^/api/v1/groups/{id}
    public function testGetGroup(): void
    {
        // get Peter Group Id
        $peterGroupId = $this->getPeterGroupId();

        // request from peter to get Peter Group detail
        self::$peter->request(
            'GET',
            \sprintf('%s/%s', $this->endpoint, $peterGroupId)
        );

        // Get response && responseData
        $response     = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        // Test Response code 200 OK && check id
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($peterGroupId, $responseData['id']);
    }

    /**
     * Test for check get group detail from another group
     * try to get information from group that brian do not belong to
     */
    public function testGetAnotherGroupData(): void
    {
        // get Peter Group Id
        $peterGroupId = $this->getPeterGroupId();

        // request from peter with brian ID to get Peter Group detail
        self::$brian->request(
            'GET',
            \sprintf('%s/%s', $this->endpoint, $peterGroupId)
        );

        // Get response for brian && responseData
        $response = self::$brian->getResponse();

        // Test Response forbidden access code 403 OK && check id
        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());

    }

}