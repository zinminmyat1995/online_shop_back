<?php
namespace App\Interfaces;

interface ProductListInterface
{

	// search product data
	function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id);

	// update product data
	function update($product_code, $product_name, $product_category, $gender,$made_in,$login_id);

	// to delete product 
	function delete($login_id,$product_code );
	
}
