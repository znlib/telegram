<?php

namespace ZnLib\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use ZnLib\Telegram\Domain\Base\BaseAction;
use ZnLib\Telegram\Domain\Entities\MessageEntity;
use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Entities\ResponseEntity;

class SaveDraftAction extends BaseAction
{

    private $text;

    public function __construct(string $text)
    {
        parent::__construct();
        $this->text = $text;
    }

    public function run(RequestEntity $messageEntity)
    {
        $responseEntity = new ResponseEntity;
        $responseEntity->setUserId($messageEntity->getUserId());
        $responseEntity->setMessage($this->text);
        $responseEntity->setMethod('saveDraft');
        return $this->response->send($responseEntity);
        /*return $this->messages->saveDraft([
            'peer' => $messageEntity->getUserId(),
            'message' => $this->text,
        ]);*/
    }
}
