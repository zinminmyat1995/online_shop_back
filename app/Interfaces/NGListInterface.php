<?php
namespace App\Interfaces;

interface NGListInterface
{

	// // search product data
	function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id);

	// // update product data
	function update($product_code, $ng_qty, $login_id);

	// // to delete product 
	function delete($login_id,$data  );

	// // to get ng detail information
	function getDetailInformation($login_id);

	// to approve ng information
	function approve($product_code,$ng_arrive_qty,$login_id);
	
}
