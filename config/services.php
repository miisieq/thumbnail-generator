<?php

declare(strict_types=1);

use App\Console\GenerateThumbnailCommand;
use App\ImageProperties\ImagePropertiesDTOConsoleDecoratorFactory;
use App\ImageProperties\ImagePropertiesService;
use App\SourceImage\SourceImagesRepository;
use App\SourceImage\SourceImagesService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use \ScriptFUSION\Byte\ByteFormatter;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator)
{
    $services = $configurator->services()->defaults();

    $services->set(Finder::class, Finder::class);
    $services->set(Filesystem::class, Filesystem::class);
    $services->set(ImagePropertiesService::class, ImagePropertiesService::class);
    $services->set(ByteFormatter::class, ByteFormatter::class);

    $configurator->parameters()->set('app.defaultSourceDirectory', $_ENV['DEFAULT_SOURCE_DIRECTORY']);

    $services
        ->set(ImagePropertiesDTOConsoleDecoratorFactory::class, ImagePropertiesDTOConsoleDecoratorFactory::class)
        ->args([
           service(ByteFormatter::class)
        ]);

    $services
        ->set(SourceImagesRepository::class, SourceImagesRepository::class)
        ->args([
            service(Finder::class),
            service(ImagePropertiesService::class)
        ]);

    $services
        ->set(SourceImagesService::class, SourceImagesService::class)
        ->args([
            service(SourceImagesRepository::class)
        ]);

    $services
        ->set(GenerateThumbnailCommand::class, GenerateThumbnailCommand::class)
        ->args([
            service(SourceImagesService::class),
            service(ImagePropertiesDTOConsoleDecoratorFactory::class),
            param('app.defaultSourceDirectory')
        ]);
};
