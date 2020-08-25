<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use Uuid;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit') ? $request->query('limit') : 10;
        $offset = $request->query('offset') ? $request->query('offset') : 0;

        $products = DB::table('products')
            ->where('owner', Auth::user()->id)
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['title', 'description']);
        $v = Validator::make($data, [
            'title' => 'required|min:3|max:255',
            'description'  => 'required|min:3',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $product = new Product($data);
        $product->id = Uuid::generate(4)->string;
        $product->owner = Auth::user()->id;
        $product->save();

        return response()->json([
            'status' => 'success',
            'data' => $product->toArray(),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Product::where([
            ['id', '=', $id],
            ['owner', '=', Auth::user()->id],
        ])->first();
        return response()->json(
            [
                'status' => 'success',
                'data' => $data,
            ],
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::where([
            ['id', '=', $id],
            ['owner', '=', Auth::user()->id],
        ])->first();

        if ($product) {
            $data = $request->only(['title', 'description']);
            $v = Validator::make($data, [
                'title' => 'required|min:3|max:255',
                'description'  => 'required|min:3',
            ]);

            if ($v->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 422);
            }

            $product->fill($data)->save();
            return response()->json([
                'status' => 'success',
                'data' => $product->toArray(),
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'errors' => 'bad request'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::where([
                ['id', '=', $id],
                ['owner', '=', Auth::user()->id],
            ])->first();

            if ($product) {
                $product->delete();
                return response()->json([
                    'status' => 'success',
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'errors' => 'bad request'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => 'bad request'
            ], 400);
        }
    }
}
