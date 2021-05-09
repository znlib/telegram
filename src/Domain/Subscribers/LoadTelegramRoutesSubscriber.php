<?php

namespace ZnLib\Telegram\Domain\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\App\Enums\KernelEventEnum;
use ZnCore\Base\Libs\App\Events\LoadConfigEvent;
use ZnCore\Domain\Traits\EntityManagerTrait;
use ZnLib\Telegram\Domain\Services\RouteService;

class LoadTelegramRoutesSubscriber implements EventSubscriberInterface
{

    use EntityManagerTrait;

    public static function getSubscribedEvents()
    {
        return [
            KernelEventEnum::AFTER_LOAD_CONFIG => 'onAfterLoadConfig',
        ];
    }

    public function onAfterLoadConfig(LoadConfigEvent $event)
    {
        $config = $event->getConfig();
        $routes = $this->extractRoutes($config);
        /** @var RouteService $routeService */
        $routeService = $event->getKernel()->getContainer()->get(RouteService::class);
        $routeService->setDefinitions($routes);
    }

    private function extractRoutes(array $config): array
    {
        $routes = [];
        foreach ($config['telegramRoutes'] as $containerConfig) {
            $requiredConfig = require($containerConfig);
            $routes = ArrayHelper::merge($routes, $requiredConfig);
        }
        return $routes;
    }
}
