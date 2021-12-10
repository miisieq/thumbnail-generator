<?php

declare(strict_types=1);

namespace App\ImageProperties;

use ScriptFUSION\Byte\ByteFormatter;

class ImagePropertiesDTOConsoleDecoratorFactory
{
    public function __construct(
        private ByteFormatter $bytesFormatter
    ) {
    }

    public function create(ImagePropertiesDTO $imagePropertiesDTO): ImagePropertiesDTOConsoleDecorator
    {
        return new ImagePropertiesDTOConsoleDecorator($imagePropertiesDTO, $this->bytesFormatter);
    }
}
