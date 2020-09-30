<?php declare(strict_types=1);

namespace Boomtown;

use Boomtown\Github\GithubRepresentative;

class RepresentativeFactory
{
    public static function make(Storage $storage): Representative
    {
        return new GithubRepresentative($storage);
    }
}