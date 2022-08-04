<?php

use Psr\Container\ContainerInterface;
use ZnCore\Arr\Helpers\ArrayHelper;
use ZnCore\ConfigManager\Interfaces\ConfigManagerInterface;
use ZnCore\Env\Helpers\EnvHelper;
use ZnLib\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use ZnLib\Telegram\Domain\Repositories\File\ConfigRepository;
use ZnLib\Telegram\Domain\Repositories\Telegram\ResponseRepository as TelegramResponseRepository;
use ZnLib\Telegram\Domain\Repositories\Test\ResponseRepository as TestResponseRepository;
use ZnLib\Telegram\Domain\Services\RouteService;

return [
    'singletons' => [
        RouteService::class => function (ContainerInterface $container) {
            /** @var ConfigManagerInterface $configManager */
            $configManager = $container->get(ConfigManagerInterface::class);
            $telegramRoutes = $configManager->get('telegramRoutes', []);
            $routeService = new RouteService();
            $routes = [];
            foreach ($telegramRoutes as $containerConfig) {
                $requiredConfig = require($containerConfig);
                $routes = ArrayHelper::merge($routes, $requiredConfig);
            }
            $routeService->setDefinitions($routes);
            return $routeService;
        },
        ResponseRepositoryInterface::class =>
            EnvHelper::isTest() ?
                TestResponseRepository::class :
                TelegramResponseRepository::class,
        ConfigRepository::class => function (ContainerInterface $container) {
            $repo = new ConfigRepository($_ENV['TELEGRAM_BOT_TOKEN'] ?? null);
            return $repo;
        },
    ],
];
