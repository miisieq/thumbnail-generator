<?php

declare(strict_types=1);

namespace Config;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        $container = new ContainerBuilder();
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__));
        $loader->load('services.php');

        return $container;
    }
}
