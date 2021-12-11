<?php

declare(strict_types=1);

namespace App\Common;

class NotSupportedMimeTypeException extends \InvalidArgumentException
{
    public function __construct(string $mimeType)
    {
        parent::__construct("Unsupported MIME type (\"$mimeType\").");
    }
}
