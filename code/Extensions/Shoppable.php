<?php

class Shoppable extends DataExtension {

	static $db = array(
		'Price' => 'Int',
		"ProductAvailable" => "Boolean",
	);


	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldsToTab("Root.Main", array(
			new TextField('Price',_t('Shoppable.Price',"Price")),
			new CheckboxField('ProductAvailable',_t('Shoppable.ProductAvailable',"Is the product available?"))
		),"Date");
		$fields->removeFieldFromTab("Root.Main","Excerpt");
		$fields->removeFieldFromTab("Root.Main","Date");
	}

	public function populateDefaults() {
		$this->owner->ProductAvailable = 1;
		parent::populateDefaults();
	}

	function getI18nPrice(){
		if (i18n::get_locale() == 'es_ES') {
			$c = number_format($this->owner->Price, 0, ',', '.');	
		}
		else if(i18n::get_locale() == 'en_US'){
			$c = number_format($this->owner->Price, 2, '.', '');
		}
		return $c;
	}

	function contentControllerInit(){
	}

}