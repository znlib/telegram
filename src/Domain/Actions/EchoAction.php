<?php

namespace ZnLib\Telegram\Domain\Actions;

use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\RequestEntity;

class EchoAction extends BaseAction
{

    public function run(RequestEntity $requestEntity)
    {
        $this->response->sendMessage($requestEntity->getMessage()->getChat()->getId(), $requestEntity->getMessage()->getText());
    }

}