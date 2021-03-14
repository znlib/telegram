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
}
