<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\NGReturnRegistrationInterface;
use App\Classes\Repositories\NGReturnRegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NGReturnRegistrationController extends Controller
{
    private NGReturnRegistrationRepository $repository;
    public function __construct(NGReturnRegistrationInterface $repository)
    {
        $this->repository = $repository;
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
