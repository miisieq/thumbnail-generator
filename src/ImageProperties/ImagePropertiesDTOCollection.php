<?php

declare(strict_types=1);

namespace App\ImageProperties;

use Ramsey\Collection\AbstractCollection;

class ImagePropertiesDTOCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ImagePropertiesDTO::class;
    }
}
