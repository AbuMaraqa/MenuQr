<?php

namespace App\Http\Controllers;

use App\Models\MenuPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $pages = MenuPage::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact('pages'));
    }
}
