<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\NGListInterface;
use App\Classes\Repositories\NGListRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NGListController extends Controller
{
    private NGListRepository $repository;
    public function __construct(NGListInterface $repository)
    {
        $this->repository = $repository;
    }


    public function search(Request $request)
    {

        $product_code = $request->product_code;
        $product_name = $request->product_name;
		$product_category = $request->product_category;
        $gender = $request->gender;
		$made_in = $request->made_in;
        $login_id = $request->login_id;

        $res = $this->repository->search($product_code,$product_name,$product_category,$gender,$made_in, $login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

    public function update(Request $request)
    {
		$product_code = $request->product_code;
        $ng_qty = $request->ng_qty;
        $login_id = $request->login_id;

        $rules = [
            "ng_qty" => 'required'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res1 = $this->repository->update($product_code, $ng_qty, $login_id);
        return response()->json(['status' => 'OK', 'message' => "Update Successfully!"], 200);

    }

    public function delete(Request $request)
    {
        $login_id = $request->login_id;
        $data = $request->data;
     
        $res = $this->repository->delete($login_id,$data);
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }
    
}
