<?php

namespace App\Repositories\Contracts;

use App\Data\ProductListFilters;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginateWithFilters(
        ProductListFilters $filters,
        int $perPage = 15,
        ?int $userId = null,
        bool $scopeToUser = false,
    ): LengthAwarePaginator;

    public function findById(int $id): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): bool;
}
