<?php

namespace App\DomainObject;

class Subscriber
{
    public const STATUS_ACTIVE   = '1';
    public const STATUS_INACTIVE = '0';

    private int    $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $subdate;
    private string $status;

    public function __construct(
        int $id, string $email,
        string $firstName, string $lastName,
        string $subdate, string $status
    ) {
        $this->id        = $id;
        $this->email     = $email;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->subdate   = $subdate;
        $this->status    = $status;
    }

    public function getId(): int        { return $this->id; }
    public function getEmail(): string  { return $this->email; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string  { return $this->lastName; }
    public function getFullName(): string  { return trim($this->firstName . ' ' . $this->lastName); }
    public function getSubdate(): string   { return $this->subdate; }
    public function getStatus(): string    { return $this->status; }
    public function isActive(): bool       { return $this->status === self::STATUS_ACTIVE; }
}
