<?php
namespace App\Interfaces;

interface NGArriveRegistrationInterface
{
	// search for autocomplete
	function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id);

	// // save ng arrive data
	function save($data, $login_id, $note);
      
}
