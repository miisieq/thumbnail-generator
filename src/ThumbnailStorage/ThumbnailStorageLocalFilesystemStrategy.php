<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

use Symfony\Component\Filesystem\Filesystem;

class ThumbnailStorageLocalFilesystemStrategy implements ThumbnailStorageStrategyInterface
{
    public function __construct(
        private Filesystem $filesystem,
        private string $localFileSystemTargetPath,
    ) {
    }

    public function persist(string $sourceFilePath, string $targetFilePath)
    {
        $this->filesystem->copy(
            $sourceFilePath,
            $this->localFileSystemTargetPath . '/' . $targetFilePath
        );
    }

    public function __toString(): string
    {
        return 'Local file system';
    }
}
