<?php

namespace App\Http\Controllers;

use App\Helpers\Basket;
use App\Http\Requests\CartRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected static $_basketItem = null;

    public function add(CartRequest $request)
    {

        $product = Product::query()
            ->where('id', $request->input('productId'))
            ->where('status', true)
            ->firstOrFail();

        $this->cart()->addProduct($product, (int)$request->input('qty'));
        $basket_item = view('components.cart.basket_item', compact('product'))->render();

        return $this->getContent(compact('basket_item'));
    }

    public function update(CartRequest $request)
    {

        $productId = 'product_'. $request->input('productId');
        $this->cart()->updateProduct($productId, (int)$request->input('qty'));

        return $this->getContent();
    }

    public function copy(Request $request)
    {

        $productIds = explode(',', $request->post('productIds'));
        $products = Product::query()
            ->where('stock', '<>', 0)
            ->where('status', true)
            ->whereIn('id', $productIds)
            ->get();

        if ($products->isNotEmpty()) {
            $quantities = explode(',', $request->post('quantities'));
            foreach ($products as $key => $product) {
                $this->cart()->addProduct($product, $quantities[$key] ?? 1);
            }
        }

        return response()->json(['status' => 'success', 'url' => route('pages.get', 'cart')]);
    }

    public function remove(Request $request)
    {

        $this->validate($request, [
            'productId' => 'required|int'
        ]);

        $this->cart()->remove('product_'. $request->input('productId'));

        return $this->getContent();
    }

    protected function getContent($mergeData = [])
    {

        $totalWeight = 0;
        $cartItems = $this->cart()->getContent();

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                $weight = (int)$cartItem->associatedModel->getData('weight');
                $totalWeight += $weight * $cartItem->quantity;
            }
        } else {
            $this->cart()->clear();
        }

        return response()->json(array_merge([
            'count' => $this->cart()->getTotalQuantity(),
            'items' => $cartItems,
            'total' => $this->cart()->getTotal(),
            'counter' => $cartItems->count(),
            'total_weight' => $totalWeight
        ], $mergeData));
    }

    protected function cart()
    {
        if (!static::$_basketItem) {
            static::$_basketItem = new Basket();
        }
        return static::$_basketItem;
    }
}
