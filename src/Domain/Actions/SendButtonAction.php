<?php

namespace ZnLib\Telegram\Domain\Actions;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Entities\ResponseEntity;
use danog\MadelineProto\APIFactory;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\MessageEntity;

class SendButtonAction extends BaseAction
{

    private $buttons;
    private $text;

    public function __construct(string $text, array $buttons)
    {
        parent::__construct();
        $this->buttons = $buttons;
        $this->text = $text;
    }

    public function run(RequestEntity $requestEntity)
    {
        $messageEntity = $requestEntity->getMessage();
        //$fromId = $messageEntity->getFrom()->getId();
        $chatId = $messageEntity->getChat()->getId();

        $responseEntity = new ResponseEntity;
        $responseEntity->setChatId($chatId);
        $responseEntity->setText($this->text);

        $responseEntity->setKeyboard($this->buttons);
        $responseEntity->setParseMode('HTML');
        $responseEntity->setDisableWebPagePreview('false');
        $responseEntity->setDisableNotification('false');
        return $this->response->send($responseEntity);
    }
}