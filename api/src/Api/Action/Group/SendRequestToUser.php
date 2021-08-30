<?php
declare(strict_types=1);

namespace App\Api\Action\Group;

use App\Entity\User;
use App\Service\Group\SendRequestToUserService;
use App\Service\Request\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class is for send request to user for invite her to group
 */
class SendRequestToUser
{

    private SendRequestToUserService $requestToUserService;

    public function __construct(SendRequestToUserService $requestToUserService)
    {

        $this->requestToUserService = $requestToUserService;
    }

    /**
     * @param Request $request
     * @param string $id idGroup
     * @param User $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $id, User $user): JsonResponse
    {
        $this->requestToUserService->send(
            $id,
            RequestService::getField($request, 'email'),
            $user->getId()
        );

        return new JsonResponse([
            'message' => 'The request has been sent to invite to group!'
        ]);
    }
}