<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Data\ProductListFilters;
use App\Http\Requests\Product\IndexProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    public function index(IndexProductRequest $request): AnonymousResourceCollection
    {
        $products = $this->productService->list(
            $request->user(),
            ProductListFilters::fromRequest($request->validated()),
        );

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            $request->user(),
            $request->validated(),
        );

        return (new ProductResource($product->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product): ProductResource
    {
        $this->authorize('view', $product);

        return new ProductResource(
            $this->productService->find($product->id) ?? $product
        );
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product = $this->productService->update($product, $request->validated());

        return new ProductResource($product->load('user'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return response()->json(['message' => 'Product moved to trash successfully.']);
    }
}
