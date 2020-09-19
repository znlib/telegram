<?php


namespace ZnLib\Telegram\Domain\Interfaces\Repositories;

use ZnLib\Telegram\Domain\Entities\BotEntity;
use ZnLib\Telegram\Domain\Entities\ResponseEntity;

interface ResponseRepositoryInterface
{

    public function send(ResponseEntity $responseEntity, BotEntity $botEntity);

}