<?php

namespace App\Models;

class Order
{
    private int $id;
    private int $userId;
    private string $orderDate;
    private string $status;

    // Constructeur pour initialiser les propriétés
    public function __construct(int $id, int $userId, string $orderDate, string $status)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderDate = $orderDate;
        $this->status = $status;
    }

    // Getter et Setter pour l'ID
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter et Setter pour user_id
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    // Getter et Setter pour order_date
    public function getOrderDate(): string
    {
        return $this->orderDate;
    }

    public function setOrderDate(string $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    // Getter et Setter pour status
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
