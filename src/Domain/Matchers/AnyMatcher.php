<?php

namespace ZnLib\Telegram\Domain\Matchers;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Interfaces\MatcherInterface;

class AnyMatcher implements MatcherInterface
{

    public function isMatch(RequestEntity $requestEntity): bool
    {
        if($requestEntity->getMessage()->getText() == '') {
            return false;
        }
        return true;
    }

}