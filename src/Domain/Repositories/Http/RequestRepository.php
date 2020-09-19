<?php


namespace ZnLib\Telegram\Domain\Repositories\Http;

use ZnLib\Telegram\Domain\Entities\ChatEntity;
use ZnLib\Telegram\Domain\Entities\FromEntity;
use ZnLib\Telegram\Domain\Entities\MessageEntity;
use ZnLib\Telegram\Domain\Entities\RequestEntity;
use ZnCore\Domain\Helpers\EntityHelper;

class RequestRepository
{

    public function getRequest(): RequestEntity {
        $request = $this->body();


        $content = '=== JSON ===';
        $content .= PHP_EOL . PHP_EOL;
        $content .= \GuzzleHttp\json_encode($request, JSON_PRETTY_PRINT);
        $content .= PHP_EOL . PHP_EOL;
        $content .= '=== HTTP QUERY ===';
        $content .= PHP_EOL . PHP_EOL;
        $content .= http_build_query($request);
        $content .= PHP_EOL . PHP_EOL;
        //dd($dd);
        //file_put_contents(__DIR__ . '/../../../../public_html/var/out.txt', $content);

        $fromEntity = new FromEntity;
        $fromEntity->setId($request['message']['from']['id']);
        $fromEntity->setIsBot($request['message']['from']['is_bot'] ?? false);
        $fromEntity->setFirstName($request['message']['from']['first_name']);
        $fromEntity->setUsername($request['message']['from']['username']);
        $fromEntity->setLanguageCode($request['message']['from']['language_code']);

        $chatEntity = new ChatEntity;
        $chatEntity->setId($request['message']['chat']['id']);
        $chatEntity->setFirstName($request['message']['chat']['first_name']);
        $chatEntity->setLastName($request['message']['chat']['last_name'] ?? null);
        $chatEntity->setUsername($request['message']['chat']['username']);
        $chatEntity->setType($request['message']['chat']['type']);

        $messageEntity = new MessageEntity;
        $messageEntity->setId($request['message']['message_id']);
        $messageEntity->setFrom($fromEntity);
        $messageEntity->setChat($chatEntity);
        $messageEntity->setDate($request['message']['date']);
        $messageEntity->setText($request['message']['text']);

        $requestEntity = new RequestEntity;
        $requestEntity->setId($request['update_id']);
        $requestEntity->setMessage($messageEntity);
        return $requestEntity;
    }

    public function getToken() {
        return $_GET['token'] ?? null;
    }

    private function body() {
        $json = file_get_contents('php://input');
        $body = json_decode($json, TRUE);
        if(empty($body)) {
            $body = $_POST;
        }
        if(empty($body)) {
            $body = $_GET;
            unset($body['token']);
        }
        if(empty($body)) {
            throw new \Exception('Empty body!');
        }
        return $body;
    }
}