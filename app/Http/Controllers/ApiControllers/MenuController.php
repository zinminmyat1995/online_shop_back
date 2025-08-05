<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\MenuInterface;
use App\Classes\Repositories\MenuRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    private MenuRepository $repository;
    public function __construct(MenuInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addMenuType(Request $request)
    {
        $menu_type = $request->menu_type;
        $login_id = $request->required;
        $rules = [
            'menu_type' => 'required',
            "login_id" => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->addMenuType($menu_type,$login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
    }

     public function search(Request $request)
    {

        $login_id = $request->login_id;
        $res = $this->repository->search($login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

    
    public function removeMenuType(Request $request)
    {
        $login_id = $request->login_id;
        $id = $request->menu_type_id;
     
        $res = $this->repository->removeMenuType($login_id,$id );
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }


    public function addMeatType(Request $request)
    {
        $meat_type = $request->meat_type;
        $login_id = $request->required;
        $rules = [
            'meat_type' => 'required',
            "login_id" => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->addMeatType($meat_type,$login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
    }

    public function searchMeat(Request $request)
    {

        $login_id = $request->login_id;
        $res = $this->repository->searchMeat($login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

    public function removeMeatType(Request $request)
    {
        $login_id = $request->login_id;
        $id = $request->meat_type_id;
     
        $res = $this->repository->removeMeatType($login_id,$id );
        return response()->json(['status' => 'OK', 'message' => "Delete Successfully!"], 200);

    }
    
    public function menuRegister(Request $request){
        $menu_id = $request->menu_id;
        $menu_name = $request->menu_name;
        $menu_type = $request->menu_type;
        $meat_and_price = $request->meat_and_price;
        $login_id = $request->login_id;
        $rules = [
            'menu_id' => 'required',
            "menu_name" => 'required',
            'menu_type' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'  =>  $validator->errors()->all()
            ], 422);
        }

        $res = $this->repository->save($menu_id, $menu_name, $menu_type,$meat_and_price,$login_id);

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
    }
    
    
}