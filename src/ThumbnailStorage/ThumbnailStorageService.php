<?php

declare(strict_types=1);

namespace App\ThumbnailStorage;

class ThumbnailStorageService
{
    private ThumbnailStorageStrategyCollection $strategies;

    public function __construct()
    {
        $this->strategies = new ThumbnailStorageStrategyCollection();
    }

    public function addStrategy(ThumbnailStorageStrategyInterface $thumbnailStorageStrategy): self
    {
        $this->strategies->add($thumbnailStorageStrategy);

        return $this;
    }

    public function getStrategies(): ThumbnailStorageStrategyCollection
    {
        return $this->strategies;
    }
}
