<?php

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use App\Exception\UnauthorizedException;
use App\Exception\TimeoutException;

class SapiClient
{
    private string $apiUser;
    private string $apiPassword;

    private const BASE_URL = 'https://api.salesautopilot.com';
    private const TIMEOUT = 10;

    public function __construct(string $apiUser, string $apiPassword)
    {
        $this->apiUser = $apiUser;
        $this->apiPassword = $apiPassword;
    }

    public function getLists(): array
    {
        return $this->request('GET', '/getlists');
    }

    public function getListCount(int $listId): int
    {
        return (int) $this->request('GET', '/listtotalcount/' . $listId);
    }

    public function getSubscribers(int $listId): array
    {
        return $this->request('POST', '/list/' . $listId . '/order/subdate/desc/20');
    }

    public function request(string $method, string $endpoint, array $data = []): mixed
    {
        $client = new Client(['base_uri' => self::BASE_URL, 'timeout' => self::TIMEOUT]);

        $options = [
            'auth' => [$this->apiUser, $this->apiPassword],
        ];

        if (strtoupper($method) === 'GET') {
            $options['query'] = $data;
        } else {
            $options['json'] = $data;
        }

        try {
            $response = $client->request($method, $endpoint, $options);
        } catch (ConnectException $e) {
            throw new TimeoutException();
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                throw new UnauthorizedException();
            }
            throw $e;
        }

        return json_decode((string) $response->getBody(), true);
    }
}
