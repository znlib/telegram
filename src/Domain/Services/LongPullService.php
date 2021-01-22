<?php

namespace ZnLib\Telegram\Domain\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnLib\Telegram\Domain\Repositories\File\StoreRepository;
use ZnLib\Telegram\Domain\Repositories\Http\UpdatesRepository;

class LongPullService
{

    protected $storeRepository;
    protected $updatesRepository;
    
    public function __construct()
    {
        $this->storeRepository = new StoreRepository();
        $this->updatesRepository = new UpdatesRepository();
    }

    public function all() {
        $lastId = $this->storeRepository->getLastId();
        $token = $this->getBotConfig('token');
        $timeout = $this->getBotConfig('timeout', 5);
        $updates = $this->updatesRepository->all($token, $lastId + 1, $timeout);
        return $updates;
    }
    
    public function setHandled($update) {
        $this->storeRepository->setLastId($update['update_id']);
    }

    public function handleUpdates(array $updates) {
        foreach ($updates as $update) {
            $this->runBot($update);
            
        }
    }

    public function runBot($update)
    {
        $token = $this->getBotConfig('token');
        $botUrl = "http://telegram-client.tpl/bot.php?token={$token}";
        $client = new Client();
        try {
            $response = $client->post($botUrl, [
                RequestOptions::JSON => $update
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
        }
        $this->setHandled($update);
    }

    protected function getBotConfig(string $name, $default = null) {
        $mainConfig = include __DIR__ . '/../../../../../../config/main.php';
        $botConfig = $mainConfig['telegram']['bot'];
        return ArrayHelper::getValue($botConfig, $name, $default);
    }
}
