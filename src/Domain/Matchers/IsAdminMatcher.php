<?php

namespace ZnLib\Telegram\Domain\Matchers;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Helpers\MatchHelper;
use ZnLib\Telegram\Domain\Interfaces\MatcherInterface;

class IsAdminMatcher implements MatcherInterface
{

    public function isMatch(RequestEntity $requestEntity): bool
    {
        $message = $requestEntity->getMessage()->getText();
        $fromId = $requestEntity->getMessage()->getFrom()->getId();
        $toId = $requestEntity->getMessage()->getChat()->getId();
        
		if(empty($fromId) || empty($toId)) {
			return false;
		}
        $isSelf = $fromId == $toId;
        $isAdmin = $fromId == $_ENV['ADMIN_ID'];
        return $isSelf || $isAdmin;
    }

}