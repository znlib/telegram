<?php

namespace ZnLib\Telegram\Domain\Libs\Loaders\BundleLoaders;

use ZnCore\Base\App\Loaders\BundleLoaders\BaseLoader;
use ZnCore\Base\Arr\Helpers\ArrayHelper;

class TelegramRoutesLoader extends BaseLoader
{

    public function loadAll(array $bundles): void
    {
        $config = [];
        foreach ($bundles as $bundle) {
            $containerConfigList = $this->load($bundle);
            if ($containerConfigList) {
                $config = ArrayHelper::merge($config, $containerConfigList);
            }
        }
        $this->getConfigManager()->set('telegramRoutes', $config);
    }
}
