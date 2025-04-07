<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use App\Interfaces\LoginInterface;
use App\Classes\Repositories\LoginRepository;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private LoginRepository $repository;
    public function __construct(LoginInterface $repository){
        $this->repository = $repository;
    }
   
    public function save(LoginRequest $request)
    {
       
        $login_id = $request->login_id;
        $password = $request->password;
        $res=$this->repository->save($login_id,$password);

        if($res){
            return response()->json(['status' => 'OK', 'message' => "Login Successfully!"], 200);
        }
        return response()->json(['status' => 'NG', 'message' => "Admin ID or Password is incorrect!"], 200);
    }
}