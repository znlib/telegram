<?php

namespace ZnLib\Telegram\Domain\Helpers;

use ZnLib\Telegram\Domain\Entities\ChatEntity;
use ZnLib\Telegram\Domain\Entities\FromEntity;
use ZnLib\Telegram\Domain\Entities\MessageEntity;
use ZnLib\Telegram\Domain\Entities\RequestEntity;

class RequestHelper
{

    public static function forgeRequestEntityFromUpdateArray(array $request)
    {
        $fromEntity = new FromEntity;
        $fromEntity->setId($request['message']['from']['id']);
        $fromEntity->setIsBot($request['message']['from']['is_bot'] ?? false);
        $fromEntity->setFirstName($request['message']['from']['first_name']);
        $fromEntity->setUsername($request['message']['from']['username']);
        $fromEntity->setLanguageCode($request['message']['from']['language_code']);

        $chatEntity = new ChatEntity;
        $chatEntity->setId($request['message']['chat']['id']);
        $chatEntity->setFirstName($request['message']['chat']['first_name']);
        $chatEntity->setLastName($request['message']['chat']['last_name'] ?? null);
        $chatEntity->setUsername($request['message']['chat']['username']);
        $chatEntity->setType($request['message']['chat']['type']);

        $messageEntity = new MessageEntity;
        $messageEntity->setId($request['message']['message_id']);
        $messageEntity->setFrom($fromEntity);
        $messageEntity->setChat($chatEntity);
        $messageEntity->setDate($request['message']['date']);
        $messageEntity->setText($request['message']['text']);

        $requestEntity = new RequestEntity;
        $requestEntity->setId($request['update_id']);
        $requestEntity->setMessage($messageEntity);
        return $requestEntity;
    }
    
}
