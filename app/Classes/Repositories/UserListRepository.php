<?php

namespace App\Classes\Repositories;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\UserListInterface;

class UserListRepository implements UserListInterface
{
    function __construct()
    {
    }

	function search($name,$address,$login_id)
    {
        $temp = array();
        $resData = array();
        $res = User::where('deleted_at', null)
		->where('name', 'LIKE', "%{$name}%")
		->where('address', 'LIKE', "%{$address}%")
        ->get()->toArray();

        if (count($res) > 0) {
            foreach ($res as $key => $value) {
                $temp['id'] = $key + 1;
				$temp['code'] = $value['code'];
                $temp['name'] = $value['name'];
                $temp['email'] = $value['email'];
                $temp['address'] = $value['address'];
                $temp['phone_no'] = $value['phone_no'];
                $temp['password'] = $value['password'];
				$temp['edit_flag'] = false;
                array_push($resData, $temp);
            }

            return $resData;
        } else {
            return [];
        }
    }

	function update($id, $name, $email, $phone_no,$address,$login_id){
		$results = User::where('deleted_at', null)
        ->where('id',$id)
        ->update([
            'name' => $name,
            'email'  => $email,
			'address' => $address,
			'phone_no' => $phone_no,
			"updated_id" => $login_id,
            "updated_at"     => now()
        ]);

        return $results;
	}

	function delete($login_id,$id ){
        $results = User::where('id', $id)
        ->update([
			'updated_id' => $login_id,
            'deleted_at'  => now()
        ]);
        return $results;
    }
}
