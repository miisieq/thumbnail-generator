<?php

declare(strict_types=1);

namespace App\SourceImage;

use Ramsey\Collection\AbstractCollection;

class ImagePropertiesDTOCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ImagePropertiesDTO::class;
    }
}
