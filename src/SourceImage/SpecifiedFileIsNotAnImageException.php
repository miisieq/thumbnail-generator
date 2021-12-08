<?php

declare(strict_types=1);

namespace App\SourceImage;

class SpecifiedFileIsNotAnImageException extends \InvalidArgumentException
{
    public function __construct(string $filePath)
    {
        parent::__construct("Specified file (\"$filePath\") is not an image!");
    }
}
