<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\SaleListInterface;
use App\Classes\Repositories\SaleListRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SaleListController extends Controller
{
    private SaleListRepository $repository;
    public function __construct(SaleListInterface $repository)
    {
        $this->repository = $repository;
    }


    public function search(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $login_id = $request->login_id;

        $res = $this->repository->search($from_date,$to_date, $login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

	public function searchData(Request $request)
    {
        $customer_id = $request->customer_id;
        $login_id = $request->login_id;

        $res = $this->repository->searchData($customer_id, $login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

    // public function update(Request $request)
    // {
	// 	$product_code = $request->product_code;
    //     $product_name = $request->product_name;
    //     $product_category = $request->product_category;
    //     $gender = $request->gender;
    //     $made_in = $request->made_in;
    //     $login_id = $request->login_id;

    //     $rules = [
    //         'product_name' => 'required',
    //         "product_category" => 'required',
    //         'gender' => 'required',
    //         "made_in" => 'required',
    //     ];
        
    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'    =>  'NG',
    //             'message'  =>  $validator->errors()->all()
    //         ], 422);
    //     }

    //     $res1 = $this->repository->update($product_code, $product_name, $product_category, $gender,$made_in,$login_id);
    //     return response()->json(['status' => 'OK', 'message' => "Update Successfully!"], 200);

    // }

    // public function delete(Request $request)
    // {
    //     $login_id = $request->login_id;
    //     $product_code = $request->product_code;
     
    //     $res = $this->repository->delete($login_id,$product_code );
    //     return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    // }
    
    
    
}
