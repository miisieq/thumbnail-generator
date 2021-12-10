<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

use Ramsey\Collection\AbstractCollection;

class ThumbnailStorageStrategyCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ThumbnailStorageStrategyInterface::class;
    }
}
