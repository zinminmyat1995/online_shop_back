<?php
namespace App\Interfaces;

interface HistoryInterface
{
  
	// to search history data
	function search($from_date,$to_date, $login_id);


}
