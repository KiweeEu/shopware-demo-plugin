<?php
declare(strict_types=1);

namespace Kiwee\KiweeDemo\Test;

use Shopware\Core\TestBootstrapper as BaseTestBootstrapper;

class TestBootstrapper extends BaseTestBootstrapper
{
    #[Override]
    public function getPluginPath(string $pluginName): ?string
    {
        $pluginDirectories = \glob($this->getProjectDir() . '/custom/*plugins/*', \GLOB_ONLYDIR) ?: [];
        $vendorDirectories = \glob($this->getProjectDir() . '/vendor/kiwee/shopware-demo-plugin', \GLOB_ONLYDIR) ?: [];
        $allPluginDirectories = array_merge($pluginDirectories, $vendorDirectories);

        foreach ($allPluginDirectories as $pluginDir) {
            if (!is_file($pluginDir . '/composer.json')) {
                continue;
            }

            if (!is_file($pluginDir . '/src/' . $pluginName . '.php')) {
                continue;
            }

            return $pluginDir;
        }

        return null;
    }
}
