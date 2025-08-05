<?php
namespace App\Interfaces;

interface MenuInterface
{
    function addMenuType($menu_type,$login_id);
    function search($login_id);
    function removeMenuType($login_id,$id );
    function addMeatType($meat_type,$login_id);
    function searchMeat($login_id);
    function removeMeatType($login_id,$id );
    function save($menu_id, $menu_name, $menu_type,$meat_and_price,$login_id);
}