<?php

class OrderItem
{
    private ?int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;
    private float $price; // Ajout de la propriété price

    // Constructeur
    public function __construct(?int $id = null, int $orderId, int $productId, int $quantity, float $price)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price; // Initialisation de la propriété price
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

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
