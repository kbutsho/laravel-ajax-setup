<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show()
    {
        return view('products');
    }
    public function getProducts(Request $request)
    {
        $perPage = 3;
        $search = $request->get('search');
        if ($search) {
            $products = Product::where('name', 'like', '%' . $search . '%')
                ->orWhere('price', 'like', '%' . $search . '%')
                ->latest()->paginate($perPage);
        } else {
            $products = Product::latest()->paginate($perPage);
        }
        $all = Product::all();
        return response()->json([
            'data' => $products,
            'products' => $all,
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage()
        ]);
    }
    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products',
            'price' => 'required',
            'image' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->image = $request->image;
        $product->save();
        $products = Product::all();
        $total = count($products);
        return response()->json([
            'status' => 'success',
            'data' => $product,
            'total' => $total
        ]);
    }
    public function updateProduct(Request $request)
    {
        $product = Product::find($request->id);
        $name = $product->name;
        $price = $product->price;
        $name = $request->name ?? $name;
        $price = $request->price ?? $price;
        Product::where('id', $request->id)->update([
            'name' => $name,
            'price' => $price
        ]);
        return response()->json([
            'status' => 'success',
        ]);
    }
    public function deleteProduct(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}
