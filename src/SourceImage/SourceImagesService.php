<?php

declare(strict_types=1);

namespace App\SourceImage;

use App\ImageProperties\ImagePropertiesDTOCollection;

class SourceImagesService
{
    public function __construct(
        private SourceImagesRepository $sourceImageRepository
    ) {
    }

    public function findAll(string $sourceDirectory): ImagePropertiesDTOCollection
    {
        return $this->sourceImageRepository->findAll($sourceDirectory);
    }
}
