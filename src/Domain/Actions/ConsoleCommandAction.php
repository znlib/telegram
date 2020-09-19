<?php

namespace ZnLib\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use danog\MadelineProto\EventHandler;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\MessageEntity;

class ConsoleCommandAction extends BaseAction
{

    public function run(MessageEntity $messageEntity)
    {
        $command = $messageEntity->getMessage();
        $command = ltrim($command, ' ~');
        if(empty($command)) {
            return $this->response->sendMessage('Empty', $messageEntity->getUserId(), $messageEntity->getId());
        }
        $result = shell_exec($command) ?? 'Completed!';
        return $this->response->sendMessage($result, $messageEntity->getUserId(), $messageEntity->getId(),);
    }

}