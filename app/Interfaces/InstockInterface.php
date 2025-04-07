<?php
namespace App\Interfaces;

interface InstockInterface
{
	// to search data
    function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id);

	// to update data
	function update($product_code, $import_qty, $ng, $price, $login_id);

	// to delete
	function delete($login_id,$product_code);
}
