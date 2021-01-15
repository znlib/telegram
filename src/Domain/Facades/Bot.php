<?php

namespace ZnLib\Telegram\Domain\Facades;

class Bot
{

    private static $responseService = null;
    private static $chatId = null;

    public static function init(string $token, int $chatId = null)
    {
        self::$chatId = $chatId;
        self::$responseService = BotFacade::getResponseService($token);
    }

    public static function dump($message, int $chatId = null, bool $isEncode = true)
    {
        self::sendMessage($message, $chatId, $isEncode);
    }

    public static function send($message, int $chatId = null, bool $isEncode = true)
    {
        self::dump($message, $chatId, $isEncode);
    }

    public static function sendAsString($message, int $chatId = null)
    {
        self::sendMessage($message, $chatId, false);
    }

    public static function sendMessage($messageData, int $chatId = null, bool $isEncode = true)
    {
        if ($isEncode) {
            $messageText = json_encode($messageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $messageText = $messageData;
        }
        $chatId = $chatId ?: self::$chatId;
        $chatId = $chatId ?: $_ENV['DUMPER_BOT_ADMIN_ID'];
        if (empty(self::$responseService)) {
            self::$responseService = BotFacade::getResponseService($_ENV['DUMPER_BOT_TOKEN']);
        }
        self::$responseService->sendMessage($chatId, $messageData);
    }
}
