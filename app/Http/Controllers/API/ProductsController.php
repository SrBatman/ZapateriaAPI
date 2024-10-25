<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Provider;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $products = Product::where('status', '=', '1')->get();

            if ($products->isEmpty()) {
                // Cifrar el mensaje
                $encryptedMessage = Crypt::encryptString('No hay productos disponibles.');
                return response()->json(['data' => [], 'message' => $encryptedMessage], 200);
            }

            // Cifrar los productos
            $encryptedProducts = $products->map(function ($product) {
                return Crypt::encryptString(json_encode($product));
            });

            // Cifrar el mensaje
            $encryptedMessage = Crypt::encryptString('Aquí están los productos 🐐');

            return response()->json(['data' => $encryptedProducts, 'message' => $encryptedMessage], 200);
        } catch (QueryException $e) {
            // Capturar errores de la base de datos
            return response()->json([
                'error' => true,
                'message' => 'No se pudo conectar a la base de datos. Por favor, inténtalo más tarde.',
            ], 500);
        } catch (\Exception $e) {
            // Capturar otros tipos de errores generales
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error inesperado. Por favor, inténtalo más tarde.',
            ], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['providers' => Provider::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->discount = $request->discount;
            $product->description = $request->description;
            $product->stock = $request->stock;
            $product->status = 1;
            $product->providerId = $request->providerId;
            $product->save();

            // Manejo de imágenes
            $this->handleProductImages($request, $product);

            $encryptedMessage = Crypt::encryptString('Producto agregado correctamente.');
            return response()->json(['product' => $product, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al agregar el producto.',
            ], 500);
        }
    }

    private function handleProductImages($request, $product)
    {
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $ext = $img->extension();
            $imgName = 'product_' . $product->id . '_1.' . $ext;
            $path = $img->storeAs('imgs/products', $imgName, 'public');
            $product->image = asset('storage/' . $path);
            $product->save();
        }

        if ($request->hasFile('image2')) {
            $img = $request->file('image2');
            $ext = $img->extension();
            $imgName = 'product_' . $product->id . '_2.' . $ext;
            $path = $img->storeAs('imgs/products', $imgName, 'public');
            $product->image2 = asset('storage/' . $path);
            $product->save();
        }

        if ($request->hasFile('image3')) {
            $img = $request->file('image3');
            $ext = $img->extension();
            $imgName = 'product_' . $product->id . '_3.' . $ext;
            $path = $img->storeAs('imgs/products', $imgName, 'public');
            $product->image3 = asset('storage/' . $path);
            $product->save();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::where('id', '=', $id)->first();

            if ($product == null) {
                $encryptedMessage = Crypt::encryptString('Producto no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            $encryptedProduct = Crypt::encryptString(json_encode($product));
            $encryptedMessage = Crypt::encryptString('Producto obtenido correctamente.');
            return response()->json(['product' => $encryptedProduct, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al obtener el producto.',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            if ($product == null) {
                $encryptedMessage = Crypt::encryptString('Producto no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            // Actualizar los campos del producto
            if ($request->name)
                $product->name = $request->name;
            if ($request->price)
                $product->price = $request->price;
            if ($request->discount)
                $product->discount = $request->discount;
            if ($request->description)
                $product->description = $request->description;
            if ($request->stock)
                $product->stock = $request->stock;
            if ($request->providerId)
                $product->providerId = $request->providerId;

            $product->save();

            // Manejo de imágenes
            $this->handleProductImages($request, $product);

            $encryptedMessage = Crypt::encryptString('Producto actualizado correctamente.');
            return response()->json(['product' => $product, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al actualizar el producto.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if ($product == null) {
                $encryptedMessage = Crypt::encryptString('Producto no encontrado.');
                return response()->json(['message' => $encryptedMessage], 404);
            }

            $product->status = 0;
            $product->save();

            $encryptedMessage = Crypt::encryptString('Producto eliminado correctamente.');
            return response()->json(['product' => $product, 'message' => $encryptedMessage], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error al eliminar el producto.',
            ], 500);
        }
    }
}