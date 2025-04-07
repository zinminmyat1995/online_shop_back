<?php
namespace App\Interfaces;

interface NGRegistrationInterface
{
	// search import data from warehouse data
	function search($product_code, $login_id);

	// save ng data
	function save($data, $login_id, $note);
      
}
