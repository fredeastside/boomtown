<?php declare(strict_types=1);

namespace Boomtown;

use DomainException;
use Generator;
use Github\Api\Organization;
use Github\Client;

class GithubStorage implements Storage
{
    public const ID = 'boomtownroi';
    private const DEFAULT_COUNT = 30;

    private Client $client;

    private Organization $organization;

    private Info $organizationInfo;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->organization = $client->api('organizations');
        $this->organizationInfo = $this->getInfo();
        $this->validatePublicRepos();
    }

    public function getPublicRepos(): Generator
    {
        $pages = $this->getPublicReposPageCount();
        for ($page = 1; $page <= $pages; $page++) {
            $repos = $this->getPublicReposByPage($page);
            foreach ($repos as $repo) {
                yield $repo;
            }
        }
    }

    public function getEvents(): array
    {
        return (new Events($this->client))->all(self::ID);
    }

    public function getHooks(): array
    {
        return $this->organization->hooks()->all(self::ID);
    }

    public function getIssues(): array
    {
        return $this->organization->issues(self::ID);
    }

    public function getMembers()
    {
        return $this->organization->members()->all(self::ID);
    }

    public function getPublicMembers()
    {
        return $this->organization->members()->all(self::ID, true);
    }

    private function getInfo(): Info
    {
        return new Info($this->organization->show(self::ID));
    }

    private function validatePublicRepos(): void
    {
        $publicReposCount = $this->organizationInfo->getPublicReposCount();
        $pages = $this->getPublicReposPageCount();
        $count = 0;
        for ($page = 1; $page <= $pages; $page++) {
            $count += count($this->getPublicReposByPage($page));
        }
        if ($publicReposCount !== $count) {
            throw new DomainException(sprintf('Incorrect public repos counts: %d != %d', $publicReposCount, $count));
        }
    }

    private function getPublicReposByPage(int $page): array
    {
        return $this->organization->repositories(self::ID, 'public', $page);
    }

    private function getPublicReposPageCount(): int
    {
        $publicReposCount = $this->organizationInfo->getPublicReposCount();

        return (int) ceil($publicReposCount / self::DEFAULT_COUNT);
    }
}