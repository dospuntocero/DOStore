<?php 
class Sale extends DataObject{

	static $db = array(
		"Name" => "Varchar(250)",
		"Email" => "Varchar(250)",
		"PaymentMethod" => "Enum('Deposit')",
		'Status' => "Enum('Unpaid,Paid','Unpaid')",
		"CartBackUp" => "HTMLText",
		"Total" => "Varchar"
	);
	
	static $searchable_fields = array(
		"Name",
		"Email",
		"Status" => "ExactMatchFilter" //unpaid appears on paid... this fixes it.
	);

	//Fields to show in the DOM
	static $summary_fields = array(
		"Name" => "Name",
		"Email" => "Email",
		"Status" => "Status",
		"Total" => "Total"
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.Main", new ReadonlyField('Name',_t('Sale.NAME',"Name")));
		$fields->addFieldToTab("Root.Main", new ReadonlyField('Email',_t('Sale.EMAIL',"Email")));
		$fields->addFieldToTab("Root.Main", new ReadonlyField('PaymentMethod',_t('Sale.PAYMENTMETHOD',"Payment Method")));
		$fields->addFieldToTab("Root.Main", new LiteralField('CartBackUp', $this->CartBackUp));
		$fields->removeFieldFromTab("Root.Main","Total");
		return $fields;
	}
	//CRUD settings
//  public function canCreate($member = null) {return false;}
//  public function canDelete($member = null) {return false;}  
}

