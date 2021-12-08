#!/usr/bin/env php
<?php

declare(strict_types=1);

use Config\ApplicationFactory;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

(new Dotenv())->load(__DIR__.'/.env');

ApplicationFactory::create()->run();
