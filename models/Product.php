<?php

namespace App\Models;

class Product
{
    private ?int $id;
    private string $name;
    private string $picture;
    private float $price;
    private int $categoryId;

    // Constructeur
    public function __construct(?int $id = null, string $name, string $picture, float $price, int $categoryId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->picture = $picture;
        $this->price = $price;
        $this->categoryId = $categoryId;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }
}
