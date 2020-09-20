<?php

namespace ZnLib\Telegram\Domain\Services;

use danog\MadelineProto\APIFactory;
use Illuminate\Container\Container;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Interfaces\MatcherInterface;
use ZnLib\Telegram\Domain\Services\SessionService;
use ZnLib\Telegram\Domain\Services\StateService;
use ZnLib\Telegram\Domain\Services\UserService;

class RouteService
{

    private $_definitions;

    public function definitions(): array
    {

    }

    public function onUpdateNewMessage(RequestEntity $requestEntity)
    {
        $this->handleMessage($requestEntity);
    }

    /**
     * @param $requestEntity
     * @return mixed
     */
    private function handleMessage(RequestEntity $requestEntity)
    {
        $definisions = $this->getDefinisions();
        foreach ($definisions as $item) {
            //$isActive = empty($item['state']) || ($item['state'] == '*' && !empty($action)) || ($item['state'] == $action);
            $isActive = 1;
            if ($isActive) {
                /** @var MatcherInterface $matcherInstance */
                $matcherInstance = $item['matcher'];
                /** @var BaseAction $actionInstance */
                $actionInstance = $item['action'];
                if ($matcherInstance->isMatch($requestEntity)) {
                    $this->humanizeResponseDelay($requestEntity);
                    $actionInstance->run($requestEntity);
                }
            }
        }
        return null;
    }

    public function setDefinitions(array $definitions)
    {
        $this->_definitions = $definitions;
    }

    private function getDefinisions()
    {
        if (empty($this->_definitions)) {
            $this->_definitions = $this->definitions();
        }
        return $this->_definitions;
    }

    private function prepareResponse(APIFactory $messages)
    {
        $container = Container::getInstance();
        /** @var ResponseService $response */
        $response = $container->get(ResponseService::class);
        $response->setApi($messages);
    }

    private function auth($update)
    {
        $container = Container::getInstance();
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);
        $userService->authByUpdate($update);
    }

    private function getStateFromSession()
    {
        $container = Container::getInstance();
        /** @var StateService $state */
        $state = $container->get(StateService::class);
        return $state->get();
    }

    private function humanizeResponseDelay($update)
    {
        if ($_ENV['APP_ENV'] == 'prod') {
            $seconds = mt_rand($_ENV['HUMANIZE_RESPONSE_DELAY_MIN'] ?? 1, $_ENV['HUMANIZE_RESPONSE_DELAY_MAX'] ?? 4);
            sleep($seconds);
        }
    }
}