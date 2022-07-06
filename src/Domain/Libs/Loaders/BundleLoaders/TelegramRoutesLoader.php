<?php

namespace ZnLib\Telegram\Domain\Libs\Loaders\BundleLoaders;

use ZnCore\Bundle\Base\BaseLoader;
use ZnCore\Arr\Helpers\ArrayHelper;

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
