<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

use Aws\S3\S3Client;

class ThumbnailStorageS3Strategy implements ThumbnailStorageStrategyInterface
{
    public function __construct(
        private S3Client $s3Client,
        private string $bucketName
    ) {}

    public function persist(string $sourceFilePath, string $targetFilePath)
    {
        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $targetFilePath,
            'Body' => fopen($sourceFilePath, 'r')
        ]);
    }

    public function __toString(): string
    {
        return 'S3 bucket';
    }
}
