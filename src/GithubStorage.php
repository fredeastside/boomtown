<?php declare(strict_types=1);

namespace Boomtown;

use Boomtown\DTO\Info;
use Github\Client;

class GithubStorage implements Storage
{
    private const ID = 'boomtownroi';

    /** @var Events  */
    private $organization;
    /**
     * @var Client
     */
    private Client $client;

    public function __construct(Client $client)
    {
        $this->organization = $client->api('organizations');
        $this->client = $client;
    }

    public function getInfo(): Info
    {
        return new Info($this->organization->show(self::ID));
    }

    public function getRepos()
    {
        return $this->organization->repositories(self::ID);
    }

    public function getEvents()
    {
        return (new Events($this->client))->all(self::ID);
    }

    public function getHooks(): array
    {
        return $this->organization->hooks()->all(self::ID);
    }

    public function getIssues(): array
    {
        return $this->organization->issues();
    }

    public function getMembers()
    {
        return $this->organization->members()->all(self::ID);
    }

    public function getPublicMembers()
    {
        return $this->organization->members()->all(self::ID, true);
    }
}