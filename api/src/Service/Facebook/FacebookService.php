<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Http\FacebookClient;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Facebook\Exceptions\FacebookSDKException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FacebookService
{
    private const ENDPOINT = '/me?fields=name,email';

    private FacebookClient $facebookClient;
    private UserRepository $userRepository;
    private EncoderService $encoderService;
    private JWTTokenManagerInterface $JWTTokenManager;

    public function __construct(
        FacebookClient $facebookClient,
        UserRepository $userRepository,
        EncoderService $encoderService,
        JWTTokenManagerInterface $JWTTokenManager
    ) {
        $this->facebookClient  = $facebookClient;
        $this->userRepository  = $userRepository;
        $this->encoderService  = $encoderService;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    /**
     * @throws FacebookSDKException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function authorize(string $accessToken): string
    {
        // request to facebook with access token for validate this token, ang get User
        try {
            $response = $this->facebookClient->get(self::ENDPOINT, $accessToken);
        } catch (\Exception $e) {
            throw new BadRequestHttpException(\sprintf('Facebook error. Message: %s', $e->getMessage()));
        }
        // Get from facebookResponse user email
        $graphUser = $response->getGraphUser();
        $email     = $graphUser->getEmail();

        // Check if the facebook account has an associated email
        if ($email === null) {
            throw new BadRequestHttpException('Facebook account without email');
        }

        // Find user in DB, if not exists create new user
        try {
            $user = $this->userRepository->findOneByEmailOrFail($email);
        } catch (UserNotFoundException $e) {
            $user = $this->createUser($graphUser->getName(), $graphUser->getEmail());
        }

        // On success return JWT
        return $this->JWTTokenManager->create($user);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createUser(string $name, string $email): User
    {
        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, \sha1(\uniqid())));
        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}
