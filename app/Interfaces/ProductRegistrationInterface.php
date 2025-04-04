<?php
namespace App\Interfaces;

interface ProductRegistrationInterface
{
	// add product category
	function addProductCategory($name,$login_id);

	// remove product category
	function removeProductCategory($login_id,$id );

	// product category search
	function productCategorySearch($login_id);

	// save product data
	function save($product_code, $product_name, $product_category,$gender,$made_in,$login_id);
	
}
