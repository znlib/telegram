<?php

namespace ZnLib\Telegram\Domain\Libs\Loaders\BundleLoaders;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\Container\Helpers\ContainerHelper;
use ZnCore\Base\Libs\App\Loaders\BundleLoaders\BaseLoader;

class TelegramRoutesLoader extends BaseLoader
{

    public function loadAll(array $bundles): array
    {
        $config = [];
        foreach ($bundles as $bundle) {
            $containerConfigList = $this->load($bundle);
            if($containerConfigList) {
                $config = ArrayHelper::merge($config, $containerConfigList);
                /*foreach ($containerConfigList as $containerConfig) {
                    $requiredConfig = require($containerConfig);
                    $config = ArrayHelper::merge($config, $requiredConfig);
                }*/
            }
        }
        $this->getConfigManager()->set('telegramRoutes', $config);
        return [$this->getName() => $config];
    }
}
