<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Models\Product;

class WebController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $major_categories = MajorCategory::all();

        $recommend_products = Product::where('recommend_flag', true)->take(3)->get();
        $recently_products = Product::orderBy('created_at', 'desc')->take(4)->get();
        $featured_products = Product::withAvg('reviews', 'score')->orderBy('reviews_avg_score', 'desc')->take(4)->get();


        // 平均評価（★）を計算する関数
        function Star_AverageRating($products)
        {
            foreach ($products as $product) {
                $product->averageRating = round($product->reviews()->avg('score'), 1);
            }
        }

        // 平均評価（★）を3回使用
        Star_AverageRating($recently_products);
        Star_AverageRating($recommend_products);
        Star_AverageRating($featured_products);


        return view('web.index', compact('major_categories', 'categories', 'recently_products', 'recommend_products', 'featured_products'));
    }
}

