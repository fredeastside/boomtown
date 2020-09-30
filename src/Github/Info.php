<?php

declare(strict_types=1);

namespace Boomtown\Github;

use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;
use Exception;

class Info
{
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;
    private int $publicReposCount;

    public function __construct(array $data)
    {
        foreach (['created_at', 'updated_at', 'public_repos'] as $param) {
            if (!array_key_exists($param, $data)) {
                throw new InvalidArgumentException(sprintf('Param %s must be set.', $param));
            }
        }
        $this->validateDates($data);
        $this->publicReposCount = (int) $data['public_repos'];
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
    public function getPublicReposCount(): int
    {
        return $this->publicReposCount;
    }

    private function validateDates(array $data): void
    {
        try {
            $this->createdAt = new DateTimeImmutable($data['created_at']);
            $this->updatedAt = new DateTimeImmutable($data['updated_at']);
        } catch (Exception $exception) {
            throw new DomainException('Invalid input dates.');
        }
        if ($this->updatedAt < $this->createdAt) {
            throw new DomainException('Updated date must be more than created.');
        }
    }
}