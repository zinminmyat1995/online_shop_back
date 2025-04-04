<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\HistoryInterface;
use App\Classes\Repositories\HistoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
	private HistoryRepository $repository;
	public function __construct(HistoryInterface $repository)
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
}
