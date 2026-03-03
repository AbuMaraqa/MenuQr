<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['media', 'menuPages' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }, 'menuPages.media'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact('categories'));
    }
}
