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
use App\ThumbnailStorage\ThumbnailStorageDropboxStrategy;
use App\ThumbnailStorage\ThumbnailStorageLocalFilesystemStrategy;
use App\ThumbnailStorage\ThumbnailStorageS3Strategy;
use App\ThumbnailStorage\ThumbnailStorageService;
use Aws\S3\S3Client;
use Imagine\Gd\Imagine;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Spatie\Dropbox\Client as DropboxClient;
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

    $configurator->parameters()->set('app.defaultSourceDirectory', $_ENV['DEFAULT_SOURCE_DIRECTORY']);
    $configurator->parameters()->set('app.localFileSystemTargetPath', $_ENV['LOCAL_FILE_SYSTEM_TARGET_PATH']);
    $configurator->parameters()->set('app.s3_bucket', $_ENV['S3_BUCKET']);
    $configurator->parameters()->set('app.s3_region', $_ENV['S3_REGION']);
    $configurator->parameters()->set('app.s3_endpoint', $_ENV['S3_ENDPOINT']);
    $configurator->parameters()->set('app.s3_key', $_ENV['S3_KEY']);
    $configurator->parameters()->set('app.s3_secret', $_ENV['S3_SECRET']);
    $configurator->parameters()->set('app.dropbox_token', $_ENV['DROPBOX_TOKEN']);

    $services->set(S3Client::class, S3Client::class)
        ->args([
            [
                'version' => 'latest',
                'region'  => param('app.s3_region'),
                'endpoint' => param('app.s3_endpoint'),
                'credentials' => [
                    'key' => param('app.s3_key'),
                    'secret' => param('app.s3_secret'),
                ],
            ]
        ]);

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

    $services->set(DropboxClient::class, DropboxClient::class)
        ->args([
            param('app.dropbox_token')
        ]);

    $services->set(ThumbnailStorageLocalFilesystemStrategy::class, ThumbnailStorageLocalFilesystemStrategy::class)
        ->args([
            service(Filesystem::class),
            param('app.localFileSystemTargetPath')
        ]);
    
    $services->set(ThumbnailStorageS3Strategy::class, ThumbnailStorageS3Strategy::class)
        ->args([
            service(S3Client::class),
            param('app.s3_bucket')
        ]);
    
    $services->set(ThumbnailStorageDropboxStrategy::class, ThumbnailStorageDropboxStrategy::class)
        ->args([
            service(DropboxClient::class)
        ]);

    $services->set(ThumbnailStorageService::class, ThumbnailStorageService::class)
        ->call('addStrategy', [service(ThumbnailStorageLocalFilesystemStrategy::class)])
        ->call('addStrategy', [service(ThumbnailStorageS3Strategy::class)])
        ->call('addStrategy', [service(ThumbnailStorageDropboxStrategy::class)])
    ;

    $services->set(GenerateThumbnailCommand::class, GenerateThumbnailCommand::class)
        ->args([
            service(FileNameGenerator::class),
            service(ImagePropertiesDTOConsoleDecoratorFactory::class),
            service(ImageResizerService::class),
            service(SourceImagesService::class),
            service(ThumbnailStorageService::class),
            param('app.defaultSourceDirectory'),
        ]);
};
