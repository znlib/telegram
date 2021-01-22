<?php


namespace ZnLib\Telegram\Domain\Repositories\File;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\Store\StoreFile;

class ConfigRepository
{

    public function getBotConfig(string $name, $default = null) {
        $mainConfig = include __DIR__ . '/../../../../../../../config/main.php';
        $botConfig = $mainConfig['telegram']['bot'];
        return ArrayHelper::getValue($botConfig, $name, $default);
    }
}
