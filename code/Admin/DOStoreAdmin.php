<?php 

class DOStoreAdmin extends ModelAdmin {
	public static $managed_models = array('Order'); // Can manage multiple models
	static $url_segment = 'store'; // Linked as /admin/products/
	static $menu_title = 'Store';
}