<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\InstockInterface;
use App\Classes\Repositories\InstockRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class InstockController extends Controller
{
    private InstockRepository $repository;
    public function __construct(InstockInterface $repository)
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
        $import_qty = $request->import_qty;
		$ng = $request->ng;
		$price = $request->price;
        $login_id = $request->login_id;

        $rules = [
            "import_qty" => 'required',
			"ng" => 'required',
			"price" => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->update($product_code, $import_qty, $ng, $price, $login_id);
		if($res){
			return response()->json(['status' => 'OK', 'message' => "Update Successfully!"], 200);
		}else{
			return response()->json(['status' => 'NG', 'message' => "Import Qty is less than NG Qty!"], 200);
		}
    

    }

    public function delete(Request $request)
    {
        $login_id = $request->login_id;
        $product_code = $request->product_code;
     
        $res = $this->repository->delete($login_id,$product_code);
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }
    
}
