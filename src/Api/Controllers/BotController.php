<?php


namespace ZnLib\Telegram\Api\Controllers;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Services\BotService;
use ZnLib\Telegram\Domain\Services\RequestService;
use ZnLib\Telegram\Domain\Services\ResponseService;
use App\Dialog\Domain\Handlers\DialogEventHandler2;

class BotController
{
    /** @var RequestService */
    private $requestService;

    /** @var ResponseService */
    private $responseService;

    /** @var BotService */
    private $botService;

    public function __construct(RequestService $requestService, ResponseService $responseService, BotService $botService)
    {
        $this->requestService = $requestService;
        $this->responseService = $responseService;
        $this->botService = $botService;
        $this->beforeAction();
    }

    public function beforeAction()
    {
        $token = $this->requestService->getToken();
        $this->botService->authByToken($token);
    }

    public function index()
    {
        $requestEntity = $this->requestService->getRequest();
        if ($requestEntity->getMessage()) {
            if ($requestEntity->getMessage()->getChat()->getType() == "private") {
                $handler = new DialogEventHandler2;
                $handler->onUpdateNewMessage($requestEntity);
            }
        } elseif ($requestEntity->getCallbackQuery()) {
            $handler = new DialogEventHandler2;
            $handler->onUpdateNewMessage($requestEntity);
        }
    }

    private function router(RequestEntity $requestEntity)
    {
        // проверяем на объект Message https://core.telegram.org/bots/api#message
        if (array_key_exists("message", $this->data)) {
            // если это текстовое сообщение объекта Message

            // если это объект СallbackQuery https://core.telegram.org/bots/api#callbackquery
        } elseif (array_key_exists("callback_query", $this->data)) {
            // получаем значение (название метода) под ключем 0 из callback_data кнопки inline
            $method = current(explode("_", $this->data['callback_query']['data']));
            // вызываем переданный метод и передаем в него весь объект callback_query
            $this->$method($this->data['callback_query']);
        } else {
            return false;
        }
    }
}
