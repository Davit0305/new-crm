<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;

class SendPushService
{
    const apiSendUrl = 'https://exp.host/--/api/v2/push/send';

    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::apiSendUrl]);
    }

    public function sendPush($token, $arData)
    {
        $payload = [
            'to' => $token,
            'sound' => 'default',
            'body' => isset($arData['body']) ? $arData['body'] : '',
            'title' => isset($arData['title']) ? $arData['title'] : ''
        ];

        try {
            $response = $this->client->post('', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Host' => 'exp.host'
                ],
                'json' => $payload
            ]);

            return $response->getBody()->getContents();

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getBody()->getContents();
            } else {
                return $e->getMessage();
            }
        }
    }
}
