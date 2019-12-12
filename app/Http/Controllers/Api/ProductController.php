<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'message' => 'OK',
            'products' => Product::where('user_id', Auth::id())->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = Validator::make($request->json()->all(), [
                'name' => ['required'],
                'description' => ['required'],
                'price' => ['required', 'numeric'],
            ])->validate();
            $data['user_id'] = Auth::id();
            if (!($user = Product::create($data))) {
                throw new \Exception('Error creating product');
            }
            return response()->json([
                'message' => 'Product created'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'validation error',
                'validation_errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Product $product)
    {
        try {
            if (!$product->isItMine())
                throw new \Exception('Product not found');
            return response()->json([
                'message' => 'ok',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        try {
            if (!$product->isItMine())
                throw new \Exception('Product not found');
            $data = Validator::make($request->json()->all(), [
                'name' => ['required'],
                'description' => ['required'],
                'price' => ['required', 'numeric'],
            ])->validate();
            $product->update($data);
            return response()->json([
                'message' => 'ok',
                'product' => $product->refresh()
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'validation error',
                'validation_errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        try {
            if (!$product->isItMine())
                throw new \Exception('Product not found');
            if (!$product->delete())
                throw new \Exception('Error removing product');
            return response()->json([
                'message' => 'ok',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
