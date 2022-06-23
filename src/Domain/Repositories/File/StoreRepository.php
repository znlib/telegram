<?php


namespace ZnLib\Telegram\Domain\Repositories\File;

use ZnCore\Base\Store\StoreFile;

class StoreRepository
{

    public function getLastId()
    {
        $storeInstance = $this->getStoreInstance();
        $storeData = $storeInstance->load();
        $lastId = $storeData['last_update_id'] ?? null;
        return $lastId;
    }

    public function setLastId($lastId)
    {
        $storeInstance = $this->getStoreInstance();
        $storeData = $storeInstance->load();
        $storeData['last_update_id'] = $lastId;
        $storeInstance->save($storeData);
    }
    
    private function getStoreInstance(): StoreFile {
        $stateFile = __DIR__ . '/../../../../../../../var/state.json';
        $storeFile = new StoreFile($stateFile);
        return $storeFile;
    }
}
