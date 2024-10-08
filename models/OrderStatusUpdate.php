<?php

class OrderStatusUpdate
{
    private ?int $id;
    private int $orderId;
    private string $status;
    private string $updatedAt;

    // Constructeur
    public function __construct(?int $id = null, int $orderId, string $status, string $updatedAt)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->status = $status;
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    // Setters
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
