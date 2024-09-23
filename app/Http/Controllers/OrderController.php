<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::all();
            return response()->json([
                'success'=>true,
                'message'=>'Orders retrieved successfully',
                'data'=>$orders
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'success'=>true,
                'message'=>'Failed to retrieved orders',
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
             
             $validated = $request->validate([
                 'customer_id' => 'required|exists:customers,id',
                 'product_id' => 'required|exists:products,id',
                 'quantity' => 'required|integer|min:1'
             ]);
     
             
             $product = Product::findOrFail($request->product_id);
     
             
             if ($product->quantity < $request->quantity) {
                 return response()->json([
                     'success' => false,
                     'message' => 'The quantity requested exceeds the quantity available.'
                 ], 400);
             }
     
             
             $totalAmount = $request->quantity * $product->price;
     
             
             $order = Order::create([
                 'customer_id' => $request->customer_id,
                 'product_id' => $request->product_id,
                 'quantity' => $request->quantity,
                 'total_amount' => $totalAmount,
             ]);
     
             
             $product->quantity -= $request->quantity;
             $product->save();
     
             return response()->json([
                 'success' => true,
                 'message' => 'Order created successfully.',
                 'data' => $order
             ], 201);
     
         } catch (\Exception $e) {
             return response()->json([
                 'success' => false,
                 'message' => 'Error creating order.',
                 'error' => $e->getMessage()
             ], 500);
         }
     }
     

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json([
                'success'=>true,
                'message'=>'Order retrieved successfully',
                'data'=>$order
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Order not found',
                
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to retrieved order',
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
            
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
    
            
            $order = Order::findOrFail($id);
    
            
            $product = Product::findOrFail($order->product_id);
    
            
            $oldQuantity = $order->quantity;
            $newQuantity = $request->quantity;
    
            
            if ($newQuantity > $oldQuantity) {
                $diff = $newQuantity - $oldQuantity;
                if ($product->quantity < $diff) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient quantity in stock for this update.'
                    ], 400);
                }
                $product->quantity -= $diff;
            } else {
                $diff = $oldQuantity - $newQuantity;
                $product->quantity += $diff;
            }
    
            
            $order->update([
                'quantity' => $newQuantity,
                'total_amount' => $newQuantity * $product->price,
            ]);
    
            
            $product->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully.',
                'data' => $order
            ], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not Found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            
            $order = Order::findOrFail($id);
    
            $product = Product::findOrFail($order->product_id);
    
            $product->quantity += $order->quantity;
            $product->save();

            $order->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Order delete successfully'
            ], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
