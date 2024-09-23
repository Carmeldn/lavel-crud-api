<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customers = Customer::all();

            return response()->json([
                'success' => true,
                'message' =>'Customers retrieved successfully',
                'data' =>$customers
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' =>'failed to retrieved customers ',
                'error' =>$e->getMessage()
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
                'first_name'=>'required',
                'last_name'=>"required",
                'email'=>'required|email|unique:customers',
                'phone'=>'required|unique:customers',
                'adress'=>'required'
            ]);
            $customer = Customer::create($validated);
            return response()->json([
                'success' =>true,
                'message' =>'Custmer created successfully',
                'date' => $customer
            ],201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' =>false,
                'message' =>'Validation failed',
                'errors' =>$e->errors()
            ],400);
        }catch (\Exception $e) {
            return response()->json([
                'success' =>false,
                'message' =>'Failed to created customer',
                'errors' =>$e->getMessage()
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json([
                'success'=>true,
                'message'=>'Custome retrieved successfully',
                'data'=>$customer
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'customer not found'
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to retrieved customer',
                'error'=>$e->getMessage()
            ],500);
        }

    }

    public function getCustomerOrders($id){
        try {
            $customer = Customer::findOrFail($id);
            $order = $customer->order;

            return response()->json([
                'success'=>true,
                'customer'=>$customer,
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Customer not found'
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
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
            $customer = Customer::findOrFail($id);
            $validated=[
                'first_name'=>'require',
                'last_name'=>'require',
                'email'=>'require|email|unique:customers,email,' . $customer->id,
                'phone'=>'require|unique:customers,phone,'. $customer->id,
                'adress'=>'require'
            ];
            
            $customer->update($validated);

            return response()->json([
                'success'=>true,
                'message'=>'Customer updated successfully',
                'data'=>$customer
            ],201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Validation Failed',
                'errors'=>$e->errors()
            ],422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Customer not found',
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to updated customer',
                'error'=>$e->getMessage()
            ],404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Customer deleted successfully',
            
        ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Customer not found'
            ],404);
        }catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>'Failed to delete customer',
                'error'=>$e->getMessage()
            ],505);
        }

    }
}
