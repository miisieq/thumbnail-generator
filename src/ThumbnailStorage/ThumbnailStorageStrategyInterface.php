<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

interface ThumbnailStorageStrategyInterface
{
    public function persist(string $sourceFilePath, string $targetFilePath);

    public function __toString(): string;
}
