<?php

declare(strict_types=1);

namespace App\Common;

use Ramsey\Uuid\UuidFactoryInterface;
use Symfony\Component\Mime\MimeTypes;

class FileNameGenerator
{
    public function __construct(
        private UuidFactoryInterface $uuidFactory,
        private MimeTypes $mimeTypes,
    ) {
    }

    public function generateFileNameWithExtensionFromMimeType(string $mimeType): string
    {
        $possibleExtensions = $this->mimeTypes->getExtensions($mimeType);

        if (empty($possibleExtensions)) {
            // TODO: Add domain exception
            throw new \Exception();
        }

        return $this->uuidFactory->uuid4()->toString() . '.' . $possibleExtensions[0];
    }
}
