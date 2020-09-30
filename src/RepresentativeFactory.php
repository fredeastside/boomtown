<?php declare(strict_types=1);

namespace Boomtown;

use Boomtown\Github\GithubRepresentative;
use Boomtown\Github\GithubStorage;
use Github\Client;

class RepresentativeFactory
{
    public static function make(): Representative
    {
        return new GithubRepresentative(new GithubStorage(new Client()));
    }
}