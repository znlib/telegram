<?php

namespace ZnLib\Telegram\Domain\Interfaces;

use ZnLib\Telegram\Domain\Entities\RequestEntity;

interface MatcherInterface
{

    public function isMatch(RequestEntity $requestEntity): bool;

}