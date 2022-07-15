<?php

namespace ZnLib\Telegram\Domain\Actions;

use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\MessageEntity;
use ZnLib\Telegram\Domain\Entities\RequestEntity;

class TypingAction extends BaseAction
{

    public function run(RequestEntity $messageEntity)
    {
        /*return $this->messages->setTyping([
            'peer' => $messageEntity->getUserId(),
            'action' => [
                '_' => 'SendMessageAction',
                'action' => 'updateUserTyping',
                'user_id' => $update['message']['from_id'],
            ],
        ]);*/
    }

}