<?php declare(strict_types=1);

namespace Boomtown\Tests;

use Boomtown\Github\GithubStorage;
use DateTime;
use DomainException;
use Github\Api\Organization;
use PHPUnit\Framework\TestCase;
use Github\Client;

class GithubStorageTest extends TestCase
{
    public function testInstance(): void
    {
        $organizationMock = $this->createOrganizationMock(
            (new DateTime())->format('c'),
            (new DateTime())->modify('+1 hour')->format('c'),
            31
        );
        $repos = [];
        for ($i = 1; $i <= 30; $i++) {
            $repos[] = ['id' => $i];
        }
        $organizationMock->expects(self::exactly(2))
            ->method('repositories')
            ->withConsecutive(
                [self::equalTo(GithubStorage::ID), 'public', 1],
                [self::equalTo(GithubStorage::ID), 'public', 2],
            )->willReturnOnConsecutiveCalls($repos, ['id' => 31]);
        $clientMock = $this->getClientMock($organizationMock);

        new GithubStorage($clientMock);
    }

    public function testInstanceWithWrongDates(): void
    {
        $organizationMock = $this->createMock(Organization::class);
        $organizationMock->method('show')->with(GithubStorage::ID)->willReturn([
            'created_at' => (new DateTime())->modify('+1 hour')->format('c'),
            'updated_at' => (new DateTime())->format('c'),
            'public_repos' => 31,
        ]);
        $clientMock = $this->getClientMock($organizationMock);

        $this->expectException(DomainException::class);
        new GithubStorage($clientMock);
    }

    public function testInstanceWithWrongReposCount(): void
    {
        $organizationMock = $this->createOrganizationMock(
            (new DateTime())->format('c'),
            (new DateTime())->modify('+1 hour')->format('c'),
            31,
        );

        $organizationMock->expects(self::exactly(2))
            ->method('repositories')
            ->with(GithubStorage::ID, 'public')->willReturn([]);

        $clientMock = $this->getClientMock($organizationMock);

        $this->expectException(DomainException::class);
        new GithubStorage($clientMock);
    }

    private function getClientMock($organizationMock)
    {
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('api')->with('organizations')->willReturn($organizationMock);

        return $clientMock;
    }

    private function createOrganizationMock($createdAt, $updatedAt, $reposCount)
    {
        $organizationMock = $this->createMock(Organization::class);
        $organizationMock->method('show')->with(GithubStorage::ID)->willReturn([
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'public_repos' => $reposCount,
        ]);

        return $organizationMock;
    }
}