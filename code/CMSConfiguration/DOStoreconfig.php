<?php

class DOStoreconfig extends DataExtension {

	static $db = array(
		"DOStoreMailTo" => "Varchar",
		"DOStoreThanks" => "HTMLText",
		"DOStoreDepositInstructions" => "HTMLText"
	);
	

	public function updateCMSFields(FieldList $fields) {
		$thanksemail = new HTMLEditorField('DOStoreThanks',_t('DOStoreconfig.DOStoreThanks',"This is the 'thank you for your purchase' text the customer will see after submitting the checkout form"));
		$depositinfo = new HTMLEditorField('DOStoreDepositInstructions',_t('DOStoreconfig.DOStoreDepositInstructions',"This is the text for the wiring instructions, deposit information, etc."));
		$thanksemail->setRows(10);
		$depositinfo->setRows(10);
		
		$fields->addFieldsToTab("Root.Store", array(
			new TextField('DOStoreMailTo',_t('DOStoreconfig.DOStoreMailTo',"the Store will Mail to this email to notify the purchase")),
			$thanksemail,
			$depositinfo
		));
	}

}
