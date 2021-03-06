<?php

namespace ZnLib\Telegram\Domain\Base;

use Illuminate\Container\Container;
use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Services\ResponseService;
use ZnLib\Telegram\Domain\Services\SessionService;
use ZnLib\Telegram\Domain\Services\StateService;
use ZnLib\Telegram\Domain\Services\UserService;

abstract class BaseAction
{

    /** @var SessionService */
    protected $session;

    /** @var StateService */
    protected $state;

    /** @var ResponseService */
    protected $response;

    public function __construct()
    {
        $container = Container::getInstance();
        //$this->session = $container->get(SessionService::class);
        //$this->state = $container->get(StateService::class);
        /** @var ResponseService $response */
        $this->response = $container->get(ResponseService::class);
        //$this->response = new ResponseService($messages, $container->get(UserService::class));
    }

    public function stateName()
    {
        return null;
    }

    abstract public function run(RequestEntity $requestEntity);

}
