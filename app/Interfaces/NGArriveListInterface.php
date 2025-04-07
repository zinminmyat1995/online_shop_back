<?php
namespace App\Interfaces;

interface NGArriveListInterface
{

	// search product data
	function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id);


	// // to delete product 
	function delete($login_id,$product_code,$ng_count ,$price , $total_price  );
	
}
