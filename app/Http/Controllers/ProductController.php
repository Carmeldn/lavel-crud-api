<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try {
        $products = Product::all();
        
        return response()->json([
            'success'=>true,
            'message'=>'Products retrieved successfully',
            'data'=>$products
        ],200);
       } catch (\Exception $e) {
        return response()->json([
            'success'=>false,
            'message'=>'Failed to retrieved products',
            'error'=>$e->getMessage()
        ],500);
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated =$request->validate([
                'category_id'=>'required|integer',
                'name'=>'required|unique:products',
                'quantity'=>'required|integer',
                'price'=>'required|integer',
            ]);
            
            $product = Product::create($validated);

            return response()->json([
                'success'=>true,
                'message'=>'Product created successfully',
                'date'=>$product
            ],200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Validation failed',
                'errors'=>$e->errors()
            ],400);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'failed to created product',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
        return response()->json([
            'success'=>true,
            'message'=>'Product retrieved successfully',
            'data'=>$product
        ],201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Product not found',
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed not retrieved product',
                'error'=>$e->getMessage()
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $validated = $request->validate([
                'category_id'=>'required|integer',
                'name'=>'required|unique:products',
                'quantity'=>'required|integer',
                'price'=>'required|integer'
            ]);
            $product ->update($validated);
            return response()->json([
                'success'=>true,
                'message'=>'Product updated successfully',
                'errors'=>$product
            ],201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Validation failed',
                'errors'=>$e->errors()
            ],422);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Product not found',
                
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to retrieved product',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        try {
            return response()->json([
                'success'=>true,
                'message'=>'product deleted successfully'
            ],201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'product not found',
                
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to deleted product',
                'error'=>$e->getMessage()
            ],500);
        }
    }
}
