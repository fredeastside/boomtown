<?php declare(strict_types=1);

namespace Boomtown;

interface Storage
{
    public function getPublicRepos();

    public function getEvents();

    public function getHooks();

    public function getIssues();

    public function getMembers();

    public function getPublicMembers();
}