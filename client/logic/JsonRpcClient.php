<?php
namespace app\logic;

use GuzzleHttp\Client,
    GuzzleHttp\RequestOptions;

class JsonRpcClient
{
    const JSON_RPC_VERSION = '2.0';
    const METHOD_URI = 'api/data';
    const BASE_SERVER_URL = 'http://172.22.0.3/';

    protected $client;

    /**
     * JsonRpcClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
            'base_uri' => self::BASE_SERVER_URL
        ]);
    }

    /**
     * Метод отправки запроса на сервер
     * @param string $method
     * @param array $params
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $method, array $params): array
    {
        $response = '{}';
        try{
            $response = $this->client
                ->post(self::METHOD_URI, [
                    RequestOptions::JSON => [
                        'jsonrpc' => self::JSON_RPC_VERSION,
                        'id' => time(),
                        'method' => $method,
                        'params' => $params
                    ]
                ])->getBody()->getContents();
        }catch (\Exception $e){
            // тут оповещаем в логи и в телеграм или еще куда то об ошибке
           echo $e->getMessage();die;
        }
        return json_decode($response, true);
    }

    /**
     * Ответ в случае ошибки
     * @return false|string
     */
    public function defaultErrorAnswer()
    {
        return [
            'status' => 'error',
            'data' => 'Произошла техническая ошибка'
        ];
    }
}