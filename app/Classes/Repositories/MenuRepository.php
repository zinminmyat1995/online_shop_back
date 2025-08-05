<?php
namespace App\Classes\Repositories;

use App\Models\Employee;
use Exception;
use App\Models\MeatType;
use App\Models\Menu;
use App\Models\MenuType;
use Illuminate\Support\Facades\DB;
use App\Interfaces\MenuInterface;
use Illuminate\Support\Carbon;

class MenuRepository implements MenuInterface
{

    function addMenuType($menu_type,$login_id){
		$data = [
			"name" => $menu_type,
            "count" => 0,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];

        $menuTypeInsert = MenuType::insert($data);
        
        return true;
    }


    function addMeatType($meat_type,$login_id){
        $data = [
			"name" => $meat_type,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];

        $menuTypeInsert = MeatType::insert($data);
        
        return true;
    }

    function search($login_id){
        $temp = array();
        $resData = array();
        $res = MenuType::where('deleted_at', null)
                ->get()->toArray();

        if (count($res) > 0) {
            return $res;
        } else {
            return [];
        }
    }

    function removeMenuType($login_id,$id ){
        $results = MenuType::where('id', $id)
        ->update([
			'updated_id' => $login_id,
            'deleted_at'  => now()
        ]);
        return $results;
    }

    function searchMeat($login_id){
        $temp = array();
        $resData = array();
        $res = MeatType::where('deleted_at', null)
                ->get()->toArray();

        if (count($res) > 0) {
            foreach ($res as $key => $value) {
                $temp['id'] = $value['id'];
				$temp['name'] = $value['name'];
                $temp['price'] = "";
                $temp['check'] = false;
                array_push($resData, $temp);
            }

            return $resData;
        } else {
            return [];
        }
    }

    function removeMeatType($login_id,$id ){
        $results = MeatType::where('id', $id)
            ->update([
                'updated_id' => $login_id,
                'deleted_at'  => now()
            ]);
        return $results;
    }

    function save($menu_id, $menu_name, $menu_type,$meat_and_price,$login_id){

       DB::beginTransaction();
        try{
            foreach ($meat_and_price as $value){
                $insert = [
                    "menu_id" =>  $menu_id,
                    "menu_name" => $menu_name,
                    "menu_type" => $menu_type,
                    "meat_type" => $value['meat_id'],
                    "price" => $value['price'],
                    "created_id" => $login_id,
                    "updated_id" => $login_id,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ];
                $create = Menu::create($insert);
            }
            

            $menuTypeRes = MenuType::where('deleted_at', null)
                ->where('id', $menu_type)
                ->get()->toArray();

			if (count($menuTypeRes) > 0) {
				$menuTypeResults = MenuType::where('deleted_at', null)
                    ->where('id', $menu_type)
                    ->update([
                        'count' => $menuTypeRes[0]['count'] + 1,
                        "updated_id" => $login_id,
                        "updated_at"     => now()
                    ]);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
			DB::rollBack();
			return false;
        }
    }
  
}
?>