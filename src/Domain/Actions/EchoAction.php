<?php

namespace ZnLib\Telegram\Domain\Actions;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Base\BaseAction2;
use ZnLib\Telegram\Domain\Entities\MessageEntity;

class EchoAction extends BaseAction2
{

    public function run(RequestEntity $requestEntity)
    {
        $this->response->sendMessage($requestEntity->getMessage()->getChat()->getId(), $requestEntity->getMessage()->getText());
    }

}