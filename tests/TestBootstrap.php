<?php
declare(strict_types=1);

require_once __DIR__ . '/TestBootstrapper.php';
use Kiwee\KiweeDemo\Test\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->setForceInstallPlugins(true)
    ->addCallingPlugin()
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('Kiwee\KiweeDemo\\Test\\', __DIR__);
