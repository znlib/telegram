<?php

namespace ZnLib\Telegram\Domain\Facades;

use ZnLib\Telegram\Domain\Facades\BotFacade;
use ZnTool\Dev\Dumper\Domain\Repositories\Telegram\DumperRepository;

class Bot
{

    private static $responseService;

    public static function dump($message, $chatId = null, bool $isEncode = true)
    {
        self::sendMessage($message, $chatId, $isEncode);
    }

    public static function send($message, $chatId = null, bool $isEncode = true)
    {
        self::dump($message, $chatId, $isEncode);
    }

    public static function sendAsString($message, $chatId = null)
    {
        self::sendMessage($message, $chatId, false);
    }

    public static function sendMessage($messageData, $chatId = null, bool $isEncode = true)
    {
        if ($isEncode) {
            $messageText = json_encode($messageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $messageText = $messageData;
        }
        $chatId = $chatId ?: $_ENV['DUMPER_BOT_ADMIN_ID'];
        if (empty(self::$responseService)) {
            self::$responseService = BotFacade::getResponseService($_ENV['DUMPER_BOT_TOKEN']);
        }
        self::$responseService->sendMessage($chatId, $messageData);
    }
}
