<?php
/**
	* Description of OrderItems
	*
	* @author Francisco
	*/
class OrderItem extends DataObject{
	static $db = array(
		'Quantity' => 'Int',
	);

	static $has_one = array(
		"Shoppable" => "Shoppable",
		'Order' => 'Order'
	);

	
	// =============================
	// = gets the item information =
	// =============================
	
	public function getItem(){
		$item = DOArticle::get()->filter(array(
			"ID" => $this->ShoppableID
		))->first();
		return $item;
	}

	// =====================
	// = subtotal per item =
	// =====================

	function getSubTotal(){
		$subtotal = $this->Quantity * $this->getItem()->Price;
		return $subtotal;
	}

	// =====================================
	// = i18n visualization for the price  =
	// =====================================

	function getI18nSubTotal(){
		if (i18n::get_locale() == 'es_ES') {
			$c = number_format($this->getSubTotal(), 0, ',', '.');
		}
		else if(i18n::get_locale() == 'en_US'){
			$c = number_format($this->getSubTotal(), 2, '.', '');
		}
		return $c;
	}
}
