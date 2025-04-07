<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\UserListInterface;
use App\Classes\Repositories\UserListRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    private UserListRepository $repository;
    public function __construct(UserListInterface $repository)
    {
        $this->repository = $repository;
    }


    public function search(Request $request)
    {
        $name = $request->name;
        $address = $request->address;
        $login_id = $request->login_id;
	

        $res = $this->repository->search($name,$address,$login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

    public function update(Request $request)
    {
		$id = $request->id;
		$name = $request->name;
        $email = $request->email;
        $phone_no = $request->phone_no;
        $address = $request->address;
        $login_id = $request->login_id;

        $rules = [
            'name' => 'required',
            "email" => 'required',
            'phone_no' => 'required',
            "address" => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res1 = $this->repository->update($id, $name, $email, $phone_no,$address,$login_id);
        return response()->json(['status' => 'OK', 'message' => "Update Successfully!"], 200);

    }

    public function delete(Request $request)
    {
        $login_id = $request->login_id;
        $id = $request->id;
     
        $res = $this->repository->delete($login_id,$id );
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }
    
    
    
}
