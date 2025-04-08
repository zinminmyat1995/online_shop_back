<?php
namespace App\Interfaces;

interface DashboardInterface
{
  
	// to search all data
	function allData($login_id);
	function getStorage($login_id);

}
