<?php

use ZnLib\Telegram\Domain\Services\BotService;

return [
    'singletons' => [
        BotService::class => BotService::class,
    ],
];
