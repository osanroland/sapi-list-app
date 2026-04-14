<?php

namespace App\Service;

use App\Client\SapiClient;
use App\DomainObject\EmailList;
use App\DomainObject\Subscriber;

class EmailListService
{
    private SapiClient $sapiClient;
    public function __construct(SapiClient $sapiClient)
    {
        $this->sapiClient = $sapiClient;
    }

    /**
     * @return EmailList[]
     */
    public function getLists(): array
    {
        $lists = [];
        foreach ($this->sapiClient->getLists() as $item) {
            $id = (int) $item['id'];
            $lists[] = new EmailList($id, $item['name'], $this->sapiClient->getListCount($id));
        }
        return $lists;
    }

    public function getListCount(int $listId): int
    {
        return $this->sapiClient->getListCount($listId);
    }

    /**
     * @return Subscriber[]
     */
    public function getSubscribers(int $listId): array
    {
        $subscribers = [];
        foreach ($this->sapiClient->getSubscribers($listId) as $item) {
            $subscribers[] = new Subscriber(
                (int) $item['id'],
                $item['email'],
                $item['mssys_firstname'] ?? '',
                $item['mssys_lastname']  ?? '',
                $item['subdate'],
                $item['active']
            );
        }
        return $subscribers;
    }

}
