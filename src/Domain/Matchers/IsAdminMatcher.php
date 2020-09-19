<?php

namespace ZnLib\Telegram\Domain\Matchers;

use ZnLib\Telegram\Domain\Interfaces\MatcherInterface;

class IsAdminMatcher implements MatcherInterface
{

    public function isMatch(array $update): bool
    {
		if(empty($update['message']['from_id']) || empty($update['message']['to_id']['user_id'])) {
			return false;
		}
        $isSelf = $update['message']['from_id'] == $update['message']['to_id']['user_id'];
        $isAdmin = $update['message']['from_id'] == $_ENV['ADMIN_ID'];
        return $isSelf || $isAdmin;
    }

}