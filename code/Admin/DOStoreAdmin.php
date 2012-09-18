<?php 

class DOStoreAdmin extends ModelAdmin {
	public static $managed_models = array('Sale'); // Can manage multiple models
	static $url_segment = 'sales'; // Linked as /admin/products/
	static $menu_title = 'Sales';
}