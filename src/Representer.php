<?php

declare(strict_types=1);

namespace Boomtown;

class Representer
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

    }
}