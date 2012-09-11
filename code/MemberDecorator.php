<?php

class MemberDecorator extends DataExtension
{
	/** //--// **/

		public function extraStatics($class = null, $extension = null) {	
		return array ( 
			'db' => array (
				'Phone' => 'Varchar(255)',
				'Company' => 'Varchar(255)',
				'Country' => 'Varchar(255)',
				'StreetAddress' => 'Varchar(255)',
				'Number' => 'Varchar(255)',
				'City' => 'Varchar(255)',
				'ZipCode' => 'Varchar(255)',
			),
		);
	}

	public function updateGeneratedCMSFields(FieldList $fields) {
		$fields->addFieldToTab("Root.Main", TextField::create('Phone', _t('Member.PHONE', 'Phone')));
		$fields->addFieldToTab("Root.Main", TextField::create('Company', _t('Member.COMPANY', 'Company')));
		$fields->addFieldToTab("Root.Main", TextField::create('Country', _t('Member.COUNTRY', 'Country')));
		$fields->addFieldToTab("Root.Main", TextField::create('StreetAddress', _t('Member.STREETADDRESS', 'Street Address')));
		$fields->addFieldToTab("Root.Main", TextField::create('Number', _t('Member.NUMBER', 'Number')));
		$fields->addFieldToTab("Root.Main", TextField::create('City', _t('Member.CITY', 'City')));
		$fields->addFieldToTab("Root.Main", TextField::create('ZipCode', _t('Member.ZIPCODE', 'Zip Code')));

		return $fields;
	}


	/** --//-- **/

	public function updateCMSFields(FieldList $fields) {
		$this->updateGeneratedCMSFields($fields);		
	}

	
	
}