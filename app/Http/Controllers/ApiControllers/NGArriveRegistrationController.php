<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\NGArriveRegistrationInterface;
use App\Classes\Repositories\NGArriveRegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NGArriveRegistrationController extends Controller
{
    private NGArriveRegistrationRepository $repository;
    public function __construct(NGArriveRegistrationInterface $repository)
    {
        $this->repository = $repository;
    }

	public function searchAllData(Request $request)
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


    public function save(Request $request)
    {
        $data = $request->data;
		$note = $request->note;
        $login_id = $request->login_id;
      
        $res = $this->repository->save($data, $login_id, $note);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
       
    }

}
