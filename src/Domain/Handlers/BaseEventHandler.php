<?php

namespace ZnLib\Telegram\Domain\Handlers;

use danog\MadelineProto\EventHandler;

abstract class BaseEventHandler extends EventHandler
{

    /**
     * Get peer(s) where to report errors.
     *
     * @return int|string|array
     */
    public function getReportPeers()
    {
        return [
            $_ENV['ADMIN_LOGIN']
        ];
    }

}