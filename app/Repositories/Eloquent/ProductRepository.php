<?php

namespace App\Repositories\Eloquent;

use App\Data\ProductListFilters;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginateWithFilters(
        ProductListFilters $filters,
        int $perPage = 15,
        ?int $userId = null,
        bool $scopeToUser = false,
    ): LengthAwarePaginator {
        $query = Product::query()
            ->with('user:id,name')
            ->latest();

        if ($scopeToUser && $userId !== null) {
            $query->where('user_id', $userId);
        }

        if ($filters->search !== null && $filters->search !== '') {
            $this->applySearch($query, $filters->search);
        }

        $this->applyDateAvailableFilter($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?Product
    {
        return Product::with('user:id,name,email')->find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }

    protected function applySearch(Builder $query, string $keyword): void
    {
        $keyword = trim($keyword);
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' && mb_strlen($keyword) >= 3) {
            $query->whereFullText(['title', 'description'], $keyword);

            return;
        }

        $escaped = str_replace(['%', '_'], ['\%', '\_'], $keyword);
        $like = '%'.$escaped.'%';

        $query->where(function ($q) use ($like) {
            $q->where('title', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }

    protected function applyDateAvailableFilter(Builder $query, ProductListFilters $filters): void
    {
        if ($filters->availableFrom !== null) {
            $query->whereDate('date_available', '>=', $filters->availableFrom);
        }

        if ($filters->availableTo !== null) {
            $query->whereDate('date_available', '<=', $filters->availableTo);
        }
    }
}
