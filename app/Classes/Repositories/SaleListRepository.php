<?php

namespace App\Classes\Repositories;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\SaleListInterface;

class SaleListRepository implements SaleListInterface
{
    function __construct()
    {
    }

	
	function search($from_date,$to_date, $login_id)
    {
		$res = array();
		$res = Sale::whereNull('sale.deleted_at')
				->join('customer', 'customer.id', '=', 'sale.customer_id')
				->leftJoin('payment', 'payment.id', '=', 'customer.payment_id')
				->whereBetween('sale.updated_at', [$from_date, $to_date])
				->select(
					'sale.*',
					'customer.*',
					'payment.name as payment_name'
					) 
				->get()
				->toArray();


        if (count($res) > 0) {
            return $res;
        } else {
            return [];
        }
    }

	function searchData($customer_id, $login_id){
		$res = array();
		$res = Sale::whereNull('sale.deleted_at')
						->join('warehouse', 'warehouse.id', '=', 'sale.warehouse_id')
						->join('product', 'warehouse.p_code', '=', 'product.p_code')
						->join('product_category', 'product.p_category_id', '=', 'product_category.id')
						->where('sale.customer_id', $customer_id)
						->select(
							'sale.price',
							'sale.qty',
							'sale.total_price',
							'product_category.p_category_name',
							'product.p_code',
							'product.p_name',
							'product.p_category_id',
							'product.p_gender',
							'product.p_made_in',
							) 
						->get()
						->toArray();
        if (count($res) > 0) {
            return $res;
        } else {
            return [];
        }
	}
	// function update($product_code, $product_name, $product_category, $gender,$made_in,$login_id){
	// 	$results = Product::where('deleted_at', null)
    //     ->where('p_code',$product_code)
    //     ->update([
    //         'p_name' => $product_name,
    //         'p_category_id'  => $product_category,
	// 		'p_gender' => $gender,
	// 		'p_made_in' => $made_in,
	// 		"updated_id" => $login_id,
    //         "updated_at"     => now()
    //     ]);

    //     return $results;
	// }

	// function delete($login_id,$product_code ){
    //     $results = Product::where('p_code', $product_code)
    //     ->update([
	// 		'updated_id' => $login_id,
    //         'deleted_at'  => now()
    //     ]);
    //     return $results;
    // }

}
