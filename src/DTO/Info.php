<?php

declare(strict_types=1);

namespace Boomtown\DTO;

use DateTimeImmutable;
use InvalidArgumentException;

class Info
{
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private int $publicRepos;

    public function __construct(array $data)
    {
        foreach (['created_at', 'updated_at', 'public_repos'] as $param) {
            if (!array_key_exists($param, $data)) {
                throw new InvalidArgumentException(sprintf('Param %s must be set.', $param));
            }
        }
        $this->createdAt = new DateTimeImmutable($data['created_at']);
        $this->updatedAt = new DateTimeImmutable($data['updated_at']);
        $this->publicRepos = (int) $data['public_repos'];
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getPublicRepos(): int
    {
        return $this->publicRepos;
    }
}