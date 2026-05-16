<?php

namespace App\Services\Product;

use App\Data\ProductListFilters;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mews\Purifier\Facades\Purifier;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $products,
    ) {}

    public function list(User $user, ProductListFilters $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->products->paginateWithFilters(
            $filters,
            $perPage,
            $user->id,
            ! $user->isAdmin(),
        );
    }

    public function find(int $id): ?Product
    {
        return $this->products->findById($id);
    }

    public function create(User $user, array $data): Product
    {
        return $this->products->create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $this->sanitizeDescription($data['description']),
            'price' => $data['price'],
            'date_available' => $data['date_available'],
        ]);
    }

    public function update(Product $product, array $data): Product
    {
        return $this->products->update($product, [
            'title' => $data['title'],
            'description' => $this->sanitizeDescription($data['description']),
            'price' => $data['price'],
            'date_available' => $data['date_available'],
        ]);
    }

    public function delete(Product $product): bool
    {
        return $this->products->delete($product);
    }

    protected function sanitizeDescription(string $html): string
    {
        return Purifier::clean($html);
    }
}
