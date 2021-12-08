<?php

declare(strict_types=1);

use App\SourceImage\SourceImagesRepository;
use App\SourceImage\SourceImagesService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use App\SourceImage\ImagePropertiesService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator)
{
    $services = $configurator->services()->defaults();

    $services->set(Finder::class, Finder::class);
    $services->set(Filesystem::class, Filesystem::class);
    $services->set(ImagePropertiesService::class, ImagePropertiesService::class);

    $configurator->parameters()->set('sourceImage.searchPath', $_ENV['EXAMPLE_DATA_PATH']);

    $services
        ->set(SourceImagesRepository::class, SourceImagesRepository::class)
        ->args([
            param('sourceImage.searchPath'),
            service(Finder::class),
            service(ImagePropertiesService::class)
        ]);

    $services
        ->set(SourceImagesService::class, SourceImagesService::class)
        ->args([
            service(SourceImagesRepository::class)
        ]);
};
