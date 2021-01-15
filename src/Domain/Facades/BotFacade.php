<?php

namespace ZnLib\Telegram\Domain\Facades;

use Illuminate\Container\Container;
use ZnCore\Base\Libs\App\Helpers\ContainerHelper;
use ZnLib\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use ZnLib\Telegram\Domain\Repositories\Telegram\ResponseRepository;
use ZnLib\Telegram\Domain\Services\BotService;
use ZnLib\Telegram\Domain\Services\RequestService;
use ZnLib\Telegram\Domain\Services\ResponseService;

class BotFacade
{

    public static function getResponseService(string $token): ResponseService
    {
        /** @var Container $container */
        $container = ContainerHelper::getContainer();
        $container->singleton(ResponseRepositoryInterface::class, ResponseRepository::class);
        $container->singleton(BotService::class, BotService::class);
        $botService = $container->get(BotService::class);
        $botService->authByToken($token);
        /** @var RequestService $requestService */
//        $requestService = $container->get(RequestService::class);
        /** @var ResponseService $responseService */
        $responseService = $container->get(ResponseService::class);
        return $responseService;
    }
}
