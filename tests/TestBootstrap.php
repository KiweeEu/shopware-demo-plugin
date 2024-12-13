<?php

declare(strict_types=1);

use Shopware\Core\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->setForceInstallPlugins(true)
    ->addCallingPlugin()
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('Kiwee\KiweeDemo\\Test\\', __DIR__);
