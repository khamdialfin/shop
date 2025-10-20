<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Aside extends Component
{
    /**
     * Create a new component instance.
     */
    public $routes;
    public function __construct()
    {
        $this->routes = [
            [
                "label" => "Dashboard",
                "icon" => "fas fa-laptop",
                "route_name" => "admin.dashboard",
                "route_active" => "admin.dashboard",
                "is_dropdown" => false,
            ],
            [
                "label" => "Master Data",
                "icon" => "fas fa-database",
                "route_active" => "admin.kategori.*",
                "is_dropdown" => true,
                "dropdown" => [
                    [
                        "label" => "Kategori",
                        "route_active" => "admin.kategori.*",
                        "route_name" => "admin.kategori.index",
                    ], [
                        "label" => "Produk",
                        "route_active" => "admin.product.*",
                        "route_name" => "admin.product.index",
                    ],
                ]
            ],
            [
                "label" => "User",
                "icon" => "fas fa-users",
                "route_name" => "admin.users.index",
                "route_active" => "admin.users.*",
                "is_dropdown" => false,
            ],
            [
                "label" => "Pesanan",
                "icon" => "fas fa-shopping-cart",
                "route_name" => "admin.orders.index",
                "route_active" => "admin.orders.*",
                "is_dropdown" => false,
            ],
            [
                "label" => "Messages",
                "icon" => "fas fa-sms",
                "route_name" => "admin.messages.index",
                "route_active" => "admin.messages.*",
                "is_dropdown" => false,
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.aside');
    }
}
