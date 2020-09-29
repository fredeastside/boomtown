<?php declare(strict_types=1);

namespace Boomtown;

interface Storage
{
    public function getInfo();

    public function getRepos();

    public function getEvents();

    public function getHooks();

    public function getIssues();

    public function getMembers();

    public function getPublicMembers();
}