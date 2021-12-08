<?php

declare(strict_types=1);

namespace App\ImageResizer;

use App\Common\FileSystemUtils;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;

class ImageResizerService
{
    public function __construct(
        private ImagineInterface $imagine,
        private FileSystemUtils $fileSystemUtils
    ) {}

    public function generateThumbnail(string $filePath, int $width): string
    {
        $outputFile = $this->fileSystemUtils->createTemporaryFile();

        $this->imagine
            ->open($filePath)
            ->thumbnail(new Box($width, 100))
            ->save($outputFile);

        return $outputFile;
    }
}
