<?php

namespace App\Data;

readonly class ProductListFilters
{
    public function __construct(
        public ?string $search = null,
        public ?string $availableFrom = null,
        public ?string $availableTo = null,
    ) {}

    public function hasAny(): bool
    {
        return filled($this->search)
            || filled($this->availableFrom)
            || filled($this->availableTo);
    }

    public static function fromRequest(array $input): self
    {
        return new self(
            search: filled($input['search'] ?? null) ? trim($input['search']) : null,
            availableFrom: $input['available_from'] ?? null,
            availableTo: $input['available_to'] ?? null,
        );
    }
}
