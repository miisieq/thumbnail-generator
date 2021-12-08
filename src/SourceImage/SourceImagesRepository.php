<?php

declare(strict_types=1);

namespace App\SourceImage;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SourceImagesRepository
{
    public function __construct(
        private string $searchPath,
        private Finder $finder,
        private ImagePropertiesService $imagePropertiesService,
    ) {}

    public function findAll(): ImagePropertiesDTOCollection
    {
        $collection = new ImagePropertiesDTOCollection();

        /** @var SplFileInfo $file */
        foreach ($this->finder->files()->in($this->searchPath) as $file) {
            $collection->add($this->imagePropertiesService->extractImagePropertiesFromFile(
                $this->searchPath . '/' . $file->getRelativePathname()
            ));
        }

        return $collection;
    }
}
