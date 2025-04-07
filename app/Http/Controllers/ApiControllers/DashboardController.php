<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\DashboardInterface;
use App\Classes\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	private DashboardRepository $repository;
	public function __construct(DashboardInterface $repository)
	{
		$this->repository = $repository;
	}


	public function getAllData(Request $request)
	{
		$login_id = $request->login_id;

		$res = $this->repository->allData($login_id);

		if (count($res) > 0) {
			return response()->json(['status' => 'OK', 'data' => $res], 200);
		}else {
			return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
		}

	}    
}
