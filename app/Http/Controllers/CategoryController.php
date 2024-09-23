<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\ModelNotFoundException ;
use function Laravel\Prompts\error;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();

            return response()->json([
                'success' => true,
                'message' =>'Categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' =>'Failed to retrieved categories',
                'error'=> $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' =>'required|unique:categories',
                'description' =>'required'
            ]);
             $category = Category:: Create($validated);
             
             return response()->json([
                'success'=>true,
                'message'=>'Category created successfully',
                'data'=> $category
             ],201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Validation failed',
                'errors'=>$e->errors()
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to create category',
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
            $category = Category::findorfail($id);
            
            return response()->json([
                'success'=>true,
                'message'=>'Category retrieved successfully',
                'date'=> $category
            ],201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Category not found'
            ],404);
        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to retrieved category'
            ],500);
        }
    }
    
    public function getCategoryProducts (string $id){
        try {
            $category  = Category::findOrFail($id);
            $products =$category->products;

            return response()->json([
                'success'=> true,
                'category' => $category->name,
                'products' => $products
            ],200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'sucees'=>false,
                'message'=>'Category not found',
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'sucees'=>false,
                'message'=>'Server error',
                'error'=>$e->getMessage()
            ],500);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'name'=>'required',
                'description'=>'required'
            ]);

            $category ->update($validated);
            return response()->json([
                'success'=>true,
                'message' =>'Category updated successfully',
                "data"=>$category
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
                'message'=>'Category not found',
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to updated category',
                'error'=>$e->getMessage()
            ],500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category ->delete();
        try {
            return response()->json([
                'success'=>true,
                'message'=>'Category deleted successfully'
            ],201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Category not found'
            ],404);

        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to delete category',
                'error'=>$e->getMessage()
            ],404);

        }
    }
}
