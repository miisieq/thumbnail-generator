<?php

namespace App\ImageProperties;

class ImagePropertiesDTO
{
    public function __construct(
        private string $fileName,
        private string $filePath,
        private string $mimeType,
        private int $size,
        private int $width,
        private int $height,
    ) {}

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
