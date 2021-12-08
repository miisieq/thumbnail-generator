<?php

declare(strict_types=1);

namespace App\SourceImage;

use App\ImageProperties\ImagePropertiesDTOCollection;
use App\ImageProperties\ImagePropertiesService;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SourceImagesRepository
{
    public function __construct(
        private Finder $finder,
        private ImagePropertiesService $imagePropertiesService,
    ) {}

    public function findAll(string $sourceDirectory): ImagePropertiesDTOCollection
    {
        $collection = new ImagePropertiesDTOCollection();

        /** @var SplFileInfo $file */
        foreach ($this->finder->files()->in($sourceDirectory) as $file) {
            $collection->add($this->imagePropertiesService->extractImagePropertiesFromFile(
                $sourceDirectory . '/' . $file->getRelativePathname()
            ));
        }

        return $collection;
    }
}
