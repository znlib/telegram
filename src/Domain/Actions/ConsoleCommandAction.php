<?php

namespace ZnLib\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use danog\MadelineProto\EventHandler;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\MessageEntity;
use ZnLib\Telegram\Domain\Entities\RequestEntity;

class ConsoleCommandAction extends BaseAction
{

    public function run(RequestEntity $requestEntity)
    {
        $messageEntity = $requestEntity->getMessage();
        //$fromId = $messageEntity->getFrom()->getId();
        $chatId = $messageEntity->getChat()->getId();
        
        
        
//        dd($requestEntity);
        $command = $requestEntity->getMessage()->getText();
        $command = ltrim($command, ' ~');
        if(empty($command)) {
            return $this->response->sendMessage($chatId, 'Empty');
        }
        $result = shell_exec($command) ?? 'Completed!';
        return $this->response->sendMessage($chatId, $result);
    }

}