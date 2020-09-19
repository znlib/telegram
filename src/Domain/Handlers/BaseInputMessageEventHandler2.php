<?php

namespace ZnLib\Telegram\Domain\Handlers;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Services\ResponseService;
use ZnLib\Telegram\Domain\Services\SessionService;
use ZnLib\Telegram\Domain\Services\StateService;
use ZnLib\Telegram\Domain\Services\UserService;
use danog\MadelineProto\APIFactory;
use Illuminate\Container\Container;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\MessageEntity;
use ZnLib\Telegram\Domain\Entities\ResponseEntity;
use ZnLib\Telegram\Domain\Interfaces\MatcherInterface;
use danog\MadelineProto\RPCErrorException;
use Exception;

abstract class BaseInputMessageEventHandler2 /*extends BaseEventHandler*/
{

    private $_definitions;

    abstract public function definitions();

    /**
     * Handle updates from supergroups and channels.
     *
     * @param array $update Update
     *
     * @return void
     */
    public function __________onUpdateNewChannelMessage(array $update) : \Generator
    {
        //return $this->onUpdateNewMessage($update);
        $this->auth($update);
        try {
            /*if ($update['message']['_'] === 'messageEmpty' || $update['message']['out'] ?? false) {
                return;
            }*/
            //yield $this->handleMessage($update, $this->messages);
            dump($update);
        } catch (RPCErrorException $e) {
            $this->report("--report: Surfaced: $e");
        } catch (Exception $e) {
            if (\stripos($e->getMessage(), 'invalid constructor given') === false) {
                $this->report("--report: Surfaced: $e");
            }
        }
    }

    public function onUpdateNewMessage(RequestEntity $requestEntity)
    {
        try {
            /*$isEmptyAuthor = empty($update['message']['user_id']) && empty($update['message']['from_id']);
            $isEmptyMessage = $update['message']['_'] === 'messageEmpty';
            if ($isEmptyAuthor || $isEmptyMessage || $update['message']['out'] ?? false) {
                return;
            }*/
            $this->handleMessage($requestEntity);
        } catch (RPCErrorException $e) {
            $this->report("--report: Surfaced: $e");
        } catch (Exception $e) {
            if (\stripos($e->getMessage(), 'invalid constructor given') === false) {
                $this->report("--report: Surfaced: $e");
            }
        }
    }

    /**
     * @param $requestEntity
     * @return mixed
     */
    private function handleMessage(RequestEntity $requestEntity)
    {
        //dd($requestEntity);
        //$this->auth($requestEntity);
        //$action = $this->getStateFromSession();
        //$this->prepareResponse($messages);
        $assoc = $this->getDefinisions();
        //dd($assoc);
        foreach ($assoc as $item) {
            //$isActive = empty($item['state']) || ($item['state'] == '*' && !empty($action)) || ($item['state'] == $action);
            $isActive = 1;
            if($isActive) {

                /** @var MatcherInterface $matcherInstance */
                $matcherInstance = $item['matcher'];
                /** @var BaseAction $actionInstance */
                $actionInstance = $item['action'];

                if ($matcherInstance->isMatch($requestEntity)) {
                    $this->humanizeResponseDelay($requestEntity);
                    //dump($actionInstance);

                    /*$messageEntity = new MessageEntity;
                    $messageEntity->setId($requestEntity['message']['id']);
                    $messageEntity->setUserId($requestEntity['message']['user_id'] ?? $requestEntity['message']['from_id']);
                    $messageEntity->setMessage($requestEntity['message']['message']);*/

                    //$messageId = isset($update['message']['id']) ? $update['message']['id'] : null;
                    //$userId = isset($update['message']['user_id']) ? $update['message']['user_id'] : null;

                    //dd($actionInstance);

                    $actionInstance->run($requestEntity);
                }
            }
        }
        return null;
    }

    private function getDefinisions() {
        if(empty($this->_definitions)) {
            $this->_definitions = $this->definitions();
        }
        return $this->_definitions;
    }

    private function prepareResponse(APIFactory $messages) {
        $container = Container::getInstance();
        /** @var ResponseService $response */
        $response = $container->get(ResponseService::class);
        $response->setApi($messages);
    }

    private function auth($update)
    {
        $container = Container::getInstance();
        //$messages = $this->messages;
        //$container->bind(APIFactory::class, function () {return $this->messages;}, true);
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);
        $userService->authByUpdate($update);
        /*if(!empty($update['message']['user_id'])) {

        } else {
            $userService->logout();
        }*/
    }

    private function getStateFromSession() {
        $container = Container::getInstance();
        /** @var StateService $state */
        $state = $container->get(StateService::class);
        return $state->get();
    }

    private function humanizeResponseDelay($update)
    {
        if ($_ENV['APP_ENV'] == 'prod') {
            $seconds = mt_rand($_ENV['HUMANIZE_RESPONSE_DELAY_MIN'] ?? 1, $_ENV['HUMANIZE_RESPONSE_DELAY_MAX'] ?? 4);
            sleep($seconds);
        }
    }

}
