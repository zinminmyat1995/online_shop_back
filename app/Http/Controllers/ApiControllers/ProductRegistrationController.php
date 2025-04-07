<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductRegistrationInterface;
use App\Classes\Repositories\ProductRegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductRegistrationController extends Controller
{
    private ProductRegistrationRepository $repository;
    public function __construct(ProductRegistrationInterface $repository)
    {
        $this->repository = $repository;
    }



    public function addProductCategory(Request $request)
    {

        $name = $request->product_category;
        $login_id = $request->login_id;
        $rules = [
            'product_category' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->addProductCategory($name,$login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Your product category is already exists!"], 200);
        }
       
    }

	
    public function removeProductCategory(Request $request)
    {
        $login_id = $request->login_id;
        $id = $request->product_category_id;
     
        $res = $this->repository->removeProductCategory($login_id,$id );
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }

	public function productCategorySearch(Request $request)
    {
        $login_id = $request->login_id;
	
        $res = $this->repository->productCategorySearch($login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }


    public function save(Request $request)
    {
        $product_code = $request->product_code;
        $product_name = $request->product_name;
        $product_category = $request->product_category;
        $gender = $request->gender;
        $made_in = $request->made_in;
        $login_id = $request->login_id;
        $rules = [
            'product_category' => 'required',
            "product_name" => 'required',
            'gender' => 'required',
            "made_in" => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->save($product_code, $product_name, $product_category,$gender,$made_in,$login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
       
    }

}
