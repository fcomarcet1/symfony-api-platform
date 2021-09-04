<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait;
    use RecreateDatabaseTrait;

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $peter = null;
    protected static ?KernelBrowser $brian = null;
    protected static ?KernelBrowser $roger = null;

    protected function setUp(): void
    {
        // anonymous client(not authenticated)
        if (self::$client === null) {

            self::$client = static::createClient();
            self::$client->setServerParameters([
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT'  => 'application/ld+json',
                ]
            );
        }

        // Authenticated clients
        if (self::$peter === null) {
            self::$peter = clone self::$client;
            $this->createAuthenticatedUser(self::$peter, 'peter@api.com');
        }

        if (self::$brian === null) {
            self::$brian = clone self::$client;
            $this->createAuthenticatedUser(self::$brian, 'brian@api.com');
        }

        if (self::$roger === null) {
            self::$roger = clone self::$client;
            $this->createAuthenticatedUser(self::$roger, 'roger@api.com');
        }
    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $email): void
    {
        $user = $this
            ->getContainer()
            ->get('App\Repository\UserRepository')
            ->findOneByEmailOrFail($email);

        $token = $this
            ->getContainer()
            ->get('Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface')
            ->create($user);

        $client->setServerParameters(
            [
                'HTTP_Authorization' => \sprintf('Bearer %s', $token),
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_ACCEPT'        => 'application/ld+json',
            ]
        );
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getPeterId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM user WHERE email = "peter@api.com"')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getPeterGroupId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM user_group WHERE name = "Peter Group"')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getPeterExpenseCategoryId()
    {
        return $this->initDbConnection()->query('SELECT id FROM category WHERE name = "Peter Expense Category"')->fetchColumn(0);
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getPeterGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->query('SELECT id FROM category WHERE name = "Peter Group Expense Category"')->fetchColumn(0);
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getPeterMovementId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM movement WHERE amount = 100')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getPeterGroupMovementId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM movement WHERE amount = 1000')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getBrianId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM user WHERE email = "brian@api.com"')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getBrianGroupId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM user_group WHERE name = "Brian Group"')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getBrianExpenseCategoryId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM category WHERE name = "Brian Expense Category"')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getBrianGroupExpenseCategoryId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM category WHERE name = "Brian Group Expense Category"')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getBrianMovementId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM movement WHERE amount = 200')
            ->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     * @throws Exception
     */
    protected function getBrianGroupMovementId()
    {
        return $this
            ->initDbConnection()
            ->executeQuery('SELECT id FROM movement WHERE amount = 2000')
            ->fetchOne();
    }
}