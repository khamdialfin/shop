<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalMessages = Message::count();
        $unreadMessages = Message::where('is_read', false)->count();
        
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentMessages = Message::with('user')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalProducts', 
            'totalOrders', 
            'totalUsers',
            'totalMessages',
            'unreadMessages', 
            'recentOrders',
            'recentMessages'
        ));

    }
}
