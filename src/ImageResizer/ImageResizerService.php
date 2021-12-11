<?php

declare(strict_types=1);

namespace App\ImageResizer;

use App\Common\TemporaryFileService;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;

class ImageResizerService
{
    public function __construct(
        private ImagineInterface $imagine,
        private TemporaryFileService $fileSystemUtils
    ) {
    }

    public function generateThumbnail(string $filePath, ImageDimensionsDTO $imageDimensionsDTO): string
    {
        $outputFile = $this->fileSystemUtils->createTemporaryFile();

        $this->imagine
            ->open($filePath)
            ->thumbnail(new Box($imageDimensionsDTO->getWidth(), $imageDimensionsDTO->getHeight()))
            ->save($outputFile);

        return $outputFile;
    }

    public function calculateSizeScaledToMaxDimension(ImageDimensionsDTO $imageDimensionsDTO, int $maxDimension): ImageDimensionsDTO
    {
        if ($imageDimensionsDTO->getWidth() > $imageDimensionsDTO->getHeight()) {
            return new ImageDimensionsDTO(
                $maxDimension,
                (int)($maxDimension * $imageDimensionsDTO->getHeight() / $imageDimensionsDTO->getWidth())
            );
        }

        if ($imageDimensionsDTO->getWidth() < $imageDimensionsDTO->getHeight()) {
            return new ImageDimensionsDTO(
                (int)($imageDimensionsDTO->getWidth() * $maxDimension / $imageDimensionsDTO->getHeight()),
                $maxDimension
            );
        }

        return new ImageDimensionsDTO($maxDimension, $maxDimension);
    }
}
