<?php

namespace ZnLib\Telegram\Domain\Factories;

use ZnCore\Base\Libs\App\Kernel;
use ZnCore\Base\Libs\App\Loaders\BundleLoader;
use ZnCore\Contract\Kernel\Interfaces\KernelInterface;
use ZnLib\Telegram\Domain\Libs\Loaders\BundleLoaders\TelegramRoutesLoader;
use ZnLib\Telegram\Domain\Subscribers\LoadTelegramRoutesSubscriber;

class KernelFactory extends \ZnCore\Base\Libs\App\Factories\KernelFactory
{

    public static function createConsoleKernel(array $bundles = []): KernelInterface
    {
        self::init();
        $bundleLoader = new BundleLoader($bundles, ['i18next', 'container', 'console', 'migration', 'telegramRoutes']);
        $bundleLoader->addLoaderConfig('telegramRoutes', TelegramRoutesLoader::class);
        $kernel = new Kernel('console');
        $kernel->setLoader($bundleLoader);
        $kernel->addSubscriber(LoadTelegramRoutesSubscriber::class);
        return $kernel;
    }
}
