<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $stats = [
            'products'      => $user->isAdmin() ? Product::count() : $user->products()->count(),
            'my_products'   => $user->products()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
