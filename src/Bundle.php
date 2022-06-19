<?php

namespace ZnLib\Telegram;

use ZnCore\Base\Libs\App\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function console(): array
    {
        return [
            'ZnLib\Telegram\Symfony4\Commands',
        ];
    }

    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/container.php',
            __DIR__ . '/Domain/config/container-script.php',
        ];
    }
}
