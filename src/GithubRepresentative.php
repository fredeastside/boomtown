<?php

declare(strict_types=1);

namespace Boomtown;

use Github\Exception\RuntimeException;
use Throwable;

class GithubRepresentative implements Representative
{
    /**
     * @var Storage
     */
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function render(): array
    {
        try {
            return [
                $this->getPublicReposIds(),
                $this->getEventsIds(),
                $this->getHooksIds(),
                $this->getIssuesIds(),
                $this->getMembersIds(),
                $this->getPublicMembersIds()
            ];
        } catch (Throwable $e) {
            throw new RepresentativeException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    private function getPublicReposIds(): ItemView
    {
        return $this->getView('getPublicRepos', 'PublicRepos');
    }

    private function getEventsIds(): ItemView
    {
        return $this->getView('getEvents', 'Events');
    }

    private function getHooksIds(): ItemView
    {
        return $this->getView('getHooks', 'Hooks');
    }

    private function getIssuesIds(): ItemView
    {
        return $this->getView('getIssues', 'Issues');
    }

    private function getMembersIds(): ItemView
    {
        return $this->getView('getMembers', 'Members');
    }

    private function getPublicMembersIds(): ItemView
    {
        return $this->getView('getPublicMembers', 'PublicMembers');
    }

    private function getView(string $method, string $title): ItemView
    {
        $view = new ItemView();
        $view->title = $title;
        try {
            $items = $this->storage->$method();
            foreach ($items as $item) {
                $view->ids[] = $item['id'];
            }
        } catch (RuntimeException $e) {
            if ($e->getCode() === 404) {
                $view->isOK = false;
            } else {
                throw $e;
            }
        }

        return $view;
    }
}