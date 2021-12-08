<?php

declare(strict_types=1);

namespace Config;

use App\Console\GenerateThumbnailCommand;
use Symfony\Component\Console\Application;

final class ApplicationFactory
{
    public static function create(): Application
    {
        $container = ContainerFactory::create();

        $application = new Application();
        $application->add($container->get(GenerateThumbnailCommand::class));
        $application->setDefaultCommand(GenerateThumbnailCommand::COMMAND_NAME);

        return $application;
    }
}
