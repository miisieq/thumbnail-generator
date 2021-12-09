<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

class ThumbnailStorageLocalFilesystemStrategy implements ThumbnailStorageStrategyInterface
{

    public function __construct()
    {
    }

    public function persist(string $sourceFilePath, string $targetFilePath)
    {
        // TODO: Implement persist() method.
    }

    public static function getStrategyIdentifier(): string
    {
        return 'local';
    }
}