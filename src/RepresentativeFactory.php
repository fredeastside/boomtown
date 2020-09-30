<?php declare(strict_types=1);

namespace Boomtown;

use Github\Client;

class RepresentativeFactory
{
    public static function make(): Representative
    {
        return new GithubRepresentative(new GithubStorage(new Client()));
    }
}