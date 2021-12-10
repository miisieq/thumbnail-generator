<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

use Spatie\Dropbox\Client;

class ThumbnailStorageDropboxStrategy implements ThumbnailStorageStrategyInterface
{
    public function __construct(
        private Client $client
    ) {
    }

    public function persist(string $sourceFilePath, string $targetFilePath)
    {
        $this->client->upload($targetFilePath, file_get_contents($sourceFilePath));
    }

    public function __toString(): string
    {
        return 'Dropbox';
    }
}
