<?php

namespace ZnLib\Telegram\Domain\Actions;

use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnLib\Telegram\Domain\Base\BaseAction;

class GroupAction extends BaseAction
{

    /** @var array | BaseAction[] */
    private $actions;

    public function __construct(array $actions)
    {
        parent::__construct();
        $this->actions = $actions;
    }

    public function run(RequestEntity $requestEntity)
    {
        foreach ($this->actions as $actionInstance) {
            $actionInstance->run($requestEntity);
        }
    }

}
