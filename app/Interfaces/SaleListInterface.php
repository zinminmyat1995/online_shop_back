<?php
namespace App\Interfaces;

interface SaleListInterface
{
  
	// to search sale data
	function search($from_date,$to_date, $login_id);

	// to search customer data
	function searchData($customer_id, $login_id);

}
