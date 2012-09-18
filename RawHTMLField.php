<?php
/**
 * Field that generates a raw html field.
 *
 *
 * @package forms
 * @subpackage fields-dataless
 */
class RawHTMLField extends DatalessField {
		
	function __construct($name) {
		parent::__construct($name);
	}
	function hasData() {
		return true;
	}

}
