<?php

use ZnCore\Base\Helpers\EnvHelper;
use ZnLib\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use ZnLib\Telegram\Domain\Repositories\Telegram\ResponseRepository as TelegramResponseRepository;
use ZnLib\Telegram\Domain\Repositories\Test\ResponseRepository as TestResponseRepository;
use ZnLib\Telegram\Domain\Services\BotService;
use ZnLib\Telegram\Domain\Services\RequestService;
use ZnLib\Telegram\Domain\Services\ResponseService;
use ZnLib\Telegram\Domain\Services\RouteService;
use ZnLib\Telegram\Domain\Repositories\File\ConfigRepository;

return [
    'definitions' => [],
    'singletons' => [
        BotService::class,
//        RequestService::class, RequestService::class,
//        ResponseService::class, ResponseService::class,
        RouteService::class,
        ResponseRepositoryInterface::class =>
            EnvHelper::isTest() ?
                TestResponseRepository::class :
                TelegramResponseRepository::class,
        ConfigRepository::class => function(\Psr\Container\ContainerInterface $container) {
            $repo = new ConfigRepository($_ENV['TELEGRAM_BOT_TOKEN'] ?? null);
            
            return $repo;
        },
    ],
];
