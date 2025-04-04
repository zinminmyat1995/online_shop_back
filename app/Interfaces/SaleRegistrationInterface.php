<?php
namespace App\Interfaces;

interface SaleRegistrationInterface
{
  
	// to search all data
	function search($product_name, $login_id);

	// to save data
	function save($data, $actual_price, $delivery_service_amount, $discount_percent , $total_price , $name , $address , $phone_no , $payment , $login_id );

}
