<?php
namespace App\Interfaces;

interface ImportRegistrationInterface
{
	// save import data 
	function save($data, $login_id, $note);
      
}
