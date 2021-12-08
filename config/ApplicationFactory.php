<?php

declare(strict_types=1);

namespace Config;

use App\Console\GenerateThumbnailCommand;
use Symfony\Component\Console\Application;
use App\SourceImage\SourceImagesService;

final class ApplicationFactory
{
    public static function create(): Application
    {
        $container = \Config\ContainerFactory::create();

        $application = new Application();
        $application->add(new GenerateThumbnailCommand(
            $container->get(SourceImagesService::class)
        ));
        $application->setDefaultCommand(GenerateThumbnailCommand::COMMAND_NAME);

        return $application;
    }
}
