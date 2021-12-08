<?php

declare(strict_types=1);

namespace App\SourceImage;

class ImagePropertiesDTOConsoleDecorator implements \Stringable
{
    private const LABEL_PATTERN = '%s resolution: %s x %s px';

    public function __construct(
        private ImagePropertiesDTO $imagePropertiesDTO
    ) {}

    public function getImagePropertiesDTO(): ImagePropertiesDTO
    {
        return $this->imagePropertiesDTO;
    }

    public function __toString(): string
    {
        return sprintf(
            self::LABEL_PATTERN,
            $this->imagePropertiesDTO->getFilePath(),
            $this->imagePropertiesDTO->getWidth(),
            $this->imagePropertiesDTO->getHeight()
        );
    }
}
