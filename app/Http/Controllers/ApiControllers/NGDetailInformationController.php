<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\NGListInterface;
use App\Classes\Repositories\NGListRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NGDetailInformationController extends Controller
{
	private NGListRepository $repository;
	public function __construct(NGListInterface $repository)
	{
		$this->repository = $repository;
	}


	public function getDetailInformation(Request $request)
	{
		$login_id = $request->login_id;

		$res = $this->repository->getDetailInformation($login_id);
		if (count($res) > 0) {
			return response()->json(['status' => 'OK', 'data' => $res], 200);
		}else {
			return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
		}

	}

	public function approve(Request $request)
	{
		$product_code = $request->product_code;
		$ng_arrive_qty = $request->ng_arrive_qty;
		$login_id = $request->login_id;

		$rules = [
			'product_code' => 'required'
		];
        
		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json([
				'status'    =>  'NG',
				'message'  =>  $validator->errors()->all()
			], 422);
		}

		$res1 = $this->repository->approve($product_code,$ng_arrive_qty,$login_id);
	
		return response()->json(['status' => 'OK', 'message' => "Approve Successfully!"], 200);

	}
	
}
