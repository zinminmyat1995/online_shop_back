<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\ImportRegistrationInterface;
use App\Classes\Repositories\ImportRegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportRegistrationController extends Controller
{
    private ImportRegistrationRepository $repository;
    public function __construct(ImportRegistrationInterface $repository)
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
