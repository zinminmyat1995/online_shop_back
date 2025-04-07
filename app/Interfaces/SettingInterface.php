<?php
namespace App\Interfaces;

interface SettingInterface
{
  
	// to save discount
    function discountSave($discount,$discount_percent, $login_id);

	// to search discount 
	function discountSearch($login_id);

	// to save payment name
	function paymentSave($payment_name, $login_id);

	// to delete payment name
	function paymentDelete($payment_name, $login_id);

	// to search payment
	function paymentSearch($login_id);

	// to search delivery
	function deliverySearch($login_id);

	// to save
	function deliverySave($delivery_service, $login_id);

	// to search print
	function printSearch($login_id);

	// to save
	function printSave($delivery_service, $login_id);

	// to get all service
	function getAllService($login_id);
    
}
