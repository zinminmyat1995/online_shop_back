<?php
namespace App\Interfaces;

interface UserListInterface
{
  
    function search($name,$address,$login_id);
	function update($id, $name, $email, $phone_no,$address,$login_id);
	function delete($login_id,$id );
    
}
