<?php

class OrderStatusUpdate
{
    private ?int $id;
    private int $orderId;
    private string $status;
    private DateTime $updatedAt;

    // Constructeur
    public function __construct(int $orderId, string $status, DateTime $updatedAt, ?int $id = null)
    {
        $this->orderId = $orderId;
        $this->status = $status;
        $this->updatedAt = $updatedAt;
        $this->id = $id;
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

    public function getUpdatedAt(): DateTime
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

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
