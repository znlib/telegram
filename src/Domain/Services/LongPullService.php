<?php

namespace ZnLib\Telegram\Domain\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Telegram\Domain\Repositories\File\ConfigRepository;
use ZnLib\Telegram\Domain\Repositories\File\StoreRepository;
use ZnLib\Telegram\Domain\Repositories\Http\UpdatesRepository;

class LongPullService
{

    protected $storeRepository;
    protected $updatesRepository;
    protected $configRepository;
    protected $logger;
    
    public function __construct(
        StoreRepository $storeRepository, 
        UpdatesRepository $updatesRepository,
        ConfigRepository $configRepository,
        LoggerInterface $logger
    )
    {
        $this->storeRepository = $storeRepository;
        $this->updatesRepository = $updatesRepository;
        $this->configRepository = $configRepository;
        $this->logger = $logger;
    }

    public function all() {
        $lastId = $this->storeRepository->getLastId();
        $token = $this->configRepository->getBotConfig('token');
        $timeout = $this->configRepository->getBotConfig('timeout', 5);
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
        $token = $this->configRepository->getBotConfig('token');
        $botUrl = "http://telegram-client.tpl/bot.php?token={$token}";
        $client = new Client();
        try {
            $response = $client->post($botUrl, [
                RequestOptions::JSON => $update
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            dump($response->getBody()->getContents());
        }
        $this->setHandled($update);
    }
}
