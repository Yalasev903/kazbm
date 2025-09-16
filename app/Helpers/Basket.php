<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Basket
{

    private $_user;

    public function __construct()
    {
        $this->_user = Auth::guard('site')->user();
    }

    public function user()
    {
        return $this->_user;
    }

    public function getContent()
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->getContent();
        }
        return \Cart::getContent();
    }

    public function getTotalQuantity()
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->getTotalQuantity();
        }
        return \Cart::getTotalQuantity();
    }

    public function getTotal()
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->getTotal();
        }
        return \Cart::getTotal();
    }

    public function clear()
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->clear();
        }
        return \Cart::clear();
    }

    public function remove($key)
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->remove($key);
        }
        return \Cart::remove($key);
    }

    public function addProduct(Product $product, int $qty = 0)
    {
        if (!$this->has('product' . '_' . $product->id)) {
            $this->add([
                'id' => 'product' . '_' . $product->id,
                'name' => $product->title,
                'price' => (int)$product->price,
                'quantity' => $qty ?: ($product->getData('pallet_count') ?: 1),
                'associatedModel' => $product,
                'attributes' => [
                    'size' => $product->size->name ?? null,
                    'color' => $product->color->name_w,
                    'image' => $product->getRealFormat('photo'),
                    'image_webp' => $product->getWebpFormat('photo'),
                    'stock_value' => $product->getStockValue(),
                    'weight' => (int)$product->getData('pallet_weight') ?: (int)$product->getData('weight'),
                    'link' => route('product.show', ['category' => $product->category->slug, 'slug' => $product->slug])
                ]
            ]);
        }
    }

    public function updateProduct(string $productId, int $qty = 0)
    {
        if ($this->has($productId)) {
            $this->update($productId, [
                'quantity' => array(
                    'relative' => false,
                    'value' => $qty
                ),
            ]);
        }
    }

    private function has($key)
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->has($key);
        }
        return \Cart::has($key);
    }

    private function add(array $data)
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->add($data);
        }
        return \Cart::add($data);
    }

    private function update($key, array $data)
    {
        if ($user = $this->_user) {
            return \Cart::session($user->id)->update($key, $data);
        }
        return \Cart::update($key, $data);
    }
}
