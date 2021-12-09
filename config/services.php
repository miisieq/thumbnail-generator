<?php

declare(strict_types=1);

use App\Common\FileNameGenerator;
use App\Common\TemporaryFileService;
use App\Console\GenerateThumbnailCommand;
use App\ImageProperties\ImagePropertiesDTOConsoleDecoratorFactory;
use App\ImageProperties\ImagePropertiesService;
use App\ImageResizer\ImageResizerService;
use App\SourceImage\SourceImagesRepository;
use App\SourceImage\SourceImagesService;
use Imagine\Gd\Imagine;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use \ScriptFUSION\Byte\ByteFormatter;
use Symfony\Component\Mime\MimeTypes;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator)
{
    $services = $configurator->services()->defaults();

    $services->set(ByteFormatter::class, ByteFormatter::class);
    $services->set(Filesystem::class, Filesystem::class);
    $services->set(Finder::class, Finder::class);
    $services->set(ImagePropertiesService::class, ImagePropertiesService::class);
    $services->set(Imagine::class, Imagine::class);
    $services->set(MimeTypes::class, MimeTypes::class);

    $services->set(UuidFactory::class)
        ->factory([Uuid::class, 'getFactory']);

    $services->set(FileNameGenerator::class, FileNameGenerator::class)
        ->args([
            service(UuidFactory::class),
            service(MimeTypes::class),
        ]);

    $services->set(TemporaryFileService::class, TemporaryFileService::class)
        ->args([
            service(Filesystem::class),
        ]);

    $configurator->parameters()->set('app.defaultSourceDirectory', $_ENV['DEFAULT_SOURCE_DIRECTORY']);

    $services->set(ImageResizerService::class, ImageResizerService::class)
        ->args([
            service(Imagine::class),
            service(TemporaryFileService::class)
        ]);

    $services->set(ImagePropertiesDTOConsoleDecoratorFactory::class, ImagePropertiesDTOConsoleDecoratorFactory::class)
        ->args([
           service(ByteFormatter::class)
        ]);

    $services->set(SourceImagesRepository::class, SourceImagesRepository::class)
        ->args([
            service(Finder::class),
            service(ImagePropertiesService::class)
        ]);

    $services->set(SourceImagesService::class, SourceImagesService::class)
        ->args([
            service(SourceImagesRepository::class)
        ]);

    $services->set(GenerateThumbnailCommand::class, GenerateThumbnailCommand::class)
        ->args([
            service(ImagePropertiesDTOConsoleDecoratorFactory::class),
            service(ImageResizerService::class),
            service(SourceImagesService::class),
            service(FileNameGenerator::class),
            param('app.defaultSourceDirectory'),
        ]);
};
