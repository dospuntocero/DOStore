<?php
/**
	* Description of Order
	* @author Francisco
	*/
class Order extends DataObject{

	static $db = array(
		'Status' => "Enum('InCart,Unpaid,Query,Paid,Processing,Sent,Complete','Unpaid')",
		'PaymentMethod' => "Enum('Deposit')",
		'ClientIdentifier' => 'Varchar',
		'PaymentStatusCode' => "Varchar(255)"
	);

	static $summary_fields = array(
		'ID' => 'Order ID',
	);

	static $has_many = array(
		'Items' => 'OrderItem'
	);

	// =================================================
	// = handles the addition of all items in the cart =
	// =================================================

	function getTotal(){
		$items = $this->Items();
		$sum = 0;
		if ($items){
			foreach ($items as $item) {
				$sum+= $item->getSubTotal();
			}
		}
		return $sum;
	}

	// ========================================================
	// = returns a correctly formated total using i18n locale =
	// ========================================================
	function getI18nTotal(){
		if (i18n::get_locale() == 'es_ES') {
			$c = number_format($this->getTotal(), 0, ',', '.');
		}
		else if(i18n::get_locale() == 'us_US'){
			$c = number_format($this->getTotal(), 2, '.', '');
		}
		return $c;
	}

}