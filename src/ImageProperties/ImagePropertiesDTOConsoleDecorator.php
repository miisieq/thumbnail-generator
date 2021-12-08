<?php

declare(strict_types=1);

namespace App\ImageProperties;

use ScriptFUSION\Byte\ByteFormatter;

class ImagePropertiesDTOConsoleDecorator implements \Stringable
{
    private const LABEL_PATTERN = '%s   size: %s    resolution: %s x %s px';

    public function __construct(
        private ImagePropertiesDTO $imagePropertiesDTO,
        private ByteFormatter $byteFormatter
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
            $this->byteFormatter->format($this->imagePropertiesDTO->getSize(), 2),
            $this->imagePropertiesDTO->getWidth(),
            $this->imagePropertiesDTO->getHeight()
        );
    }
}
