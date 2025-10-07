<?php

namespace App\View\Components\Product;

use Closure;
use App\Models\Kategori;
use App\Models\Product;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FormProduct extends Component
{
    /**
     * Create a new component instance.
     */
    public $id,$product,$kategoris;
    public $name, $description, $price, $stock, $kategori_id, $image;
    public function __construct($id = null)
    {
        $this->id = $id;
        $this->kategoris = Kategori::all();

        if ($id) {
            $this->product = Product::find($id);
            if ($this->product) {
                $this->name = $this->product->name;
                $this->description = $this->product->description;
                $this->price = $this->product->price;
                $this->stock = $this->product->stock;
                $this->kategori_id = $this->product->kategori_id;
                $this->image = $this->product->image;
            }
        } else {
            $this->product = new Product();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product.form-product');
    }
}
