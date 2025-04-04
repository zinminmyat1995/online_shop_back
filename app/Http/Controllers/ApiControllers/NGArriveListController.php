<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\NGArriveListInterface;
use App\Classes\Repositories\NGArriveListRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NGArriveListController extends Controller
{
    private NGArriveListRepository $repository;
    public function __construct(NGArriveListInterface $repository)
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

    public function delete(Request $request)
    {
        $login_id = $request->login_id;
        $product_code = $request->product_code;
        $ng_count = $request->ng_count;
		$price = $request->price;
        $total_price = $request->total_price;
     
        $res = $this->repository->delete($login_id,$product_code,$ng_count ,$price , $total_price  );
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }
    
}
