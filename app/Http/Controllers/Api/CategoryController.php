<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get(Request $request) {
        $categories = Category::orderBy('name', 'ASC')->get();
        return response()->json([
            'status' => 200,
            'categories' => $categories
        ]);
    }
}
