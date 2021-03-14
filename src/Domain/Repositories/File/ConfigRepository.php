<?php


namespace ZnLib\Telegram\Domain\Repositories\File;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\Store\StoreFile;

class ConfigRepository
{

    public function getBotToken() {
        return $_ENV['TELEGRAM_BOT_TOKEN'];
    }

    public function getLongpullTimeout() {
        return $this->getBotConfig('timeout', 5);
    }
    
    private function getBotConfig(string $name, $default = null) {
        $mainConfig = include __DIR__ . '/../../../../../../../config/main.php';
        $botConfig = $mainConfig['telegram']['bot'];
        return ArrayHelper::getValue($botConfig, $name, $default);
    }
}
