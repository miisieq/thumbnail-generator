<?php

declare(strict_types=1);

namespace App\ImageProperties;

use App\SourceImage\SpecifiedFileIsNotAnImageException;

class ImagePropertiesService
{
    public function extractImagePropertiesFromFile(string $filePath): ImagePropertiesDTO
    {
        $imageSize = getimagesize($filePath);

        if (!$imageSize) {
            throw new SpecifiedFileIsNotAnImageException($filePath);
        }

        return new ImagePropertiesDTO(
            basename($filePath),
            $filePath,
            $imageSize['mime'],
            filesize($filePath),
            $imageSize[0],
            $imageSize[1],
        );
    }
}
