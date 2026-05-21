<?php

declare(strict_types=1);

namespace App;

use MongoDB\Model\BSONDocument;

final class Product
{
    private const float VAT_RATE = 0.15;

    public function __construct(
        private string $id = '',
        private string $name = '',
        private string $category = '',
        private float $basePrice = 0.0,
        private int $quantity = 0,
        private string $description = '',
        private string $registrationDate = ''
    ) {
        if ($this->registrationDate === '') {
            $this->registrationDate = (new \DateTimeImmutable())->format('c');
        }
    }

    public static function fromBson(BSONDocument $document): self
    {
        return new self(
            id: (string) $document['_id'],
            name: (string) $document['name'],
            category: (string) $document['category'],
            basePrice: (float) $document['basePrice'],
            quantity: (int) $document['quantity'],
            description: (string) ($document['description'] ?? ''),
            registrationDate: (string) $document['registrationDate']
        );
    }

    public function toBson(): array
    {
        return [
            'name' => $this->name,
            'category' => $this->category,
            'basePrice' => $this->basePrice,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'registrationDate' => $this->registrationDate,
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getBasePrice(): float
    {
        return $this->basePrice;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }

    public function getTax(): float
    {
        return round($this->basePrice * self::VAT_RATE, 2);
    }

    public function getPriceWithTax(): float
    {
        return round($this->basePrice + $this->getTax(), 2);
    }

    public function getTotal(): float
    {
        return round($this->getPriceWithTax() * $this->quantity, 2);
    }

    public static function getVatRate(): float
    {
        return self::VAT_RATE;
    }
}
