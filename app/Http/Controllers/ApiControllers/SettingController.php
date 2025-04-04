<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\SettingInterface;
use App\Classes\Repositories\SettingRespository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    private SettingRespository $repository;
    public function __construct(SettingInterface $repository)
    {
        $this->repository = $repository;
    }

    
    public function discountSave(Request $request)
    {
        $discount = $request->discount;
        $discount_percent = $request->discount_percent;
        $login_id = $request->login_id;
        $rules = [
            'discount' => 'required',
            "discount_percent" => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->discountSave($discount,$discount_percent, $login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => $res], 200);
        }
       
    }

	public function discountSearch(Request $request)
    {
        $login_id = $request->login_id;
        $res = $this->repository->discountSearch($login_id);

        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

	public function paymentSave(Request $request)
    {
        $payment_name = $request->payment_name;
        $login_id = $request->login_id;
        $rules = [
            'payment_name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->paymentSave($payment_name, $login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Payment name is already exists!"], 200);
        }
       
    }

	public function paymentDelete(Request $request)
    {
        $payment_name = $request->payment_name;
        $login_id = $request->login_id;
        $rules = [
            'payment_name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->paymentDelete($payment_name, $login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to delete!"], 200);
        }
       
    }


	public function paymentSearch(Request $request)
    {
        $login_id = $request->login_id;
        $res = $this->repository->paymentSearch($login_id);

        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

	public function deliverySearch(Request $request)
    {
        $login_id = $request->login_id;
        $res = $this->repository->deliverySearch($login_id);

        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }
	
	public function deliverySave(Request $request)
    {
        $delivery_service = $request->delivery_service;
        $login_id = $request->login_id;
        $rules = [
            'delivery_service' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->deliverySave($delivery_service, $login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => $res], 200);
        }
       
    }

    public function printSearch(Request $request)
    {
        $login_id = $request->login_id;
        $res = $this->repository->printSearch($login_id);

        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }
	
    public function printSave(Request $request)
    {
        $print_service = $request->print_service;
        $login_id = $request->login_id;
        $rules = [
            'print_service' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->printSave($print_service, $login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => $res], 200);
        }
       
    }

    public function getAllService(Request $request)
    {
        $login_id = $request->login_id;
        $res = $this->repository->getAllService($login_id);

        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }
    
}
