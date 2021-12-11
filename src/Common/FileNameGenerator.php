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

    public function generateRandomFileNameWithExtensionFromMimeType(string $mimeType): string
    {
        $possibleExtensions = $this->mimeTypes->getExtensions($mimeType);

        if (empty($possibleExtensions)) {
           throw new NotSupportedMimeTypeException($mimeType);
        }

        return $this->uuidFactory->uuid4()->toString() . '.' . $possibleExtensions[0];
    }
}
