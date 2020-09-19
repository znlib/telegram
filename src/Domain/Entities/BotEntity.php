<?php


namespace ZnLib\Telegram\Domain\Entities;


class BotEntity
{

    private $id;
    private $key;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    public function getToken(): string {
        return $this->getId() . ':' . $this->getKey();
    }
}