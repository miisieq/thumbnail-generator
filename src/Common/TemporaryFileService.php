<?php

namespace App\Common;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class TemporaryFileService
{
    private array $temporaryFiles = [];

    public function __construct(
        private SymfonyFilesystem $filesystem
    ) {}

    public function __destruct()
    {
        $this->filesystem->remove($this->temporaryFiles);
    }

    public function createTemporaryFile(): string
    {
        $temporaryFilePath = $this->filesystem->tempnam(sys_get_temp_dir(), 'thumbnail-generator_');

        $this->temporaryFiles[] = $temporaryFilePath;

        return $temporaryFilePath;
    }
}
