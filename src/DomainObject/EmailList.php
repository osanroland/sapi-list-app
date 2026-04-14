<?php

namespace App\DomainObject;

class EmailList
{
    private int $id;
    private string $name;
    private int $subscribersCount;

    public function __construct(int $id, string $name, int $subscribersCount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->subscribersCount = $subscribersCount;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSubscribersCount(): int { return $this->subscribersCount; }
}

